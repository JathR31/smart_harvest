<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SMSService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class InboxMessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_null_conversation_messages_do_not_cross_contaminate(): void
    {
        // Simulate pre-threading data: relax the NOT NULL constraint the
        // backfill migration applies, so we can insert rows the way they
        // existed before 2026_02_28_000001_add_threading_and_sms_to_messages_table.
        Schema::table('messages', function (Blueprint $table) {
            $table->string('conversation_id')->nullable()->change();
        });

        $farmerA = User::factory()->create(['role' => 'Farmer']);
        $adminA = User::factory()->create(['role' => 'Admin']);
        $farmerB = User::factory()->create(['role' => 'Farmer']);
        $adminB = User::factory()->create(['role' => 'Admin']);

        $now = now();

        // Insert directly via the query builder (bypassing Message::boot(),
        // which would auto-assign a UUID) to reproduce true legacy rows.
        $messageAId = DB::table('messages')->insertGetId([
            'sender_id' => $farmerA->id,
            'receiver_id' => $adminA->id,
            'subject' => 'Pair A private question',
            'content' => 'Pair A secret content',
            'is_read' => false,
            'priority' => 'normal',
            'conversation_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $messageBId = DB::table('messages')->insertGetId([
            'sender_id' => $farmerB->id,
            'receiver_id' => $adminB->id,
            'subject' => 'Pair B private question',
            'content' => 'Pair B secret content',
            'is_read' => false,
            'priority' => 'normal',
            'conversation_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Run the actual backfill migration's logic.
        $migration = require database_path('migrations/2026_08_05_000001_backfill_legacy_message_conversation_ids.php');
        $migration->up();

        $this->assertSame(0, DB::table('messages')->whereNull('conversation_id')->count());

        $conversationIdA = DB::table('messages')->where('id', $messageAId)->value('conversation_id');
        $conversationIdB = DB::table('messages')->where('id', $messageBId)->value('conversation_id');
        $this->assertNotSame($conversationIdA, $conversationIdB);

        // Opening pair A's conversation must never touch or expose pair B's message.
        $this->actingAs($adminA)
            ->getJson("/api/messages/{$messageAId}")
            ->assertOk()
            ->assertJsonCount(1, 'conversation')
            ->assertJsonPath('conversation.0.content', 'Pair A secret content');

        $this->assertDatabaseHas('messages', [
            'id' => $messageBId,
            'is_read' => false,
        ]);
    }

    public function test_farmer_and_admin_can_exchange_messages_in_one_conversation(): void
    {
        $farmer = User::factory()->create(['role' => 'Farmer']);
        User::factory()->create(['role' => 'Admin']);
        // The test SQLite schema permits the legacy Admin role. The inbox API
        // itself is role-agnostic, so DA Admin accounts use this same flow.
        $admin = User::factory()->create([
            'role' => 'Admin',
            'admin_type' => 'da_admin',
            'phone_number' => '09171234567',
        ]);

        $sms = $this->fakeSmsService(true);
        $this->app->instance(SMSService::class, $sms);

        $this->actingAs($farmer)
            ->getJson('/api/officers')
            ->assertOk()
            ->assertJsonFragment(['id' => $admin->id]);

        $this->actingAs($admin)
            ->getJson('/api/farmers')
            ->assertOk()
            ->assertJsonFragment(['id' => $farmer->id]);

        $created = $this->actingAs($farmer)
            ->postJson('/api/messages', [
                'subject' => 'Crop question',
                'content' => 'When should I harvest?',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.receiver_id', (string) $admin->id)
            ->assertJsonPath('data.sent_as_sms', true);

        $messageId = $created->json('data.id');
        $conversationId = $created->json('data.conversation_id');

        $this->actingAs($admin)
            ->getJson('/api/messages')
            ->assertOk()
            ->assertJsonPath('received.0.id', $messageId);

        $this->actingAs($admin)
            ->getJson("/api/messages/{$messageId}")
            ->assertOk()
            ->assertJsonCount(1, 'conversation');

        $this->assertDatabaseHas('messages', [
            'id' => $messageId,
            'is_read' => true,
        ]);

        $this->actingAs($admin)
            ->postJson("/api/messages/{$messageId}/reply", [
                'content' => 'You can harvest next week.',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

        $this->actingAs($farmer)
            ->getJson("/api/messages/{$messageId}")
            ->assertOk()
            ->assertJsonCount(2, 'conversation')
            ->assertJsonPath('conversation.1.content', 'You can harvest next week.');

        $this->actingAs($farmer)
            ->postJson('/api/messages', [
                'subject' => 'Crop question',
                'content' => 'Thank you for the guidance.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.conversation_id', $conversationId);

        $this->actingAs($admin)
            ->getJson('/api/messages')
            ->assertOk()
            ->assertJsonCount(1, 'conversations')
            ->assertJsonPath('conversations.0.latest_content', 'Thank you for the guidance.')
            ->assertJsonPath('conversations.0.unread_count', 1);

        $this->assertSame(2, $sms->sentCount);
    }

    public function test_farmer_message_is_saved_when_sms_notification_fails(): void
    {
        $farmer = User::factory()->create(['role' => 'Farmer']);
        $admin = User::factory()->create([
            'role' => 'Admin',
            'phone_number' => '09171234567',
        ]);

        $this->app->instance(SMSService::class, $this->fakeSmsService(false));

        $response = $this->actingAs($farmer)
            ->postJson('/api/messages', [
                'subject' => 'Urgent crop concern',
                'content' => 'Please call me when you are available.',
            ])
            ->assertCreated()
            ->assertJsonPath('data.receiver_id', (string) $admin->id);

        $this->assertDatabaseHas('messages', [
            'id' => $response->json('data.id'),
            'receiver_id' => $admin->id,
            'sent_as_sms' => true,
            'sms_status' => 'failed',
        ]);
    }

    private function fakeSmsService(bool $success): SMSService
    {
        return new class($success) extends SMSService {
            public int $sentCount = 0;

            public function __construct(private readonly bool $success)
            {
            }

            public function sendMessage($phoneNumber, $message, $senderName = 'SmartHarvest')
            {
                $this->sentCount++;

                return [
                    'success' => $this->success,
                    'message' => $this->success ? 'Sent' : 'SMS provider unavailable',
                ];
            }
        };
    }
}
