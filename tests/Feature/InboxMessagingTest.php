<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SMSService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InboxMessagingTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertSame(1, $sms->sentCount);
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
