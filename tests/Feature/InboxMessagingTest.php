<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InboxMessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmer_and_admin_can_exchange_messages_in_one_conversation(): void
    {
        $farmer = User::factory()->create(['role' => 'Farmer']);
        // The test SQLite schema permits the legacy Admin role. The inbox API
        // itself is role-agnostic, so DA Admin accounts use this same flow.
        $admin = User::factory()->create(['role' => 'Admin']);

        $created = $this->actingAs($farmer)
            ->postJson('/api/messages', [
                'receiver_id' => (string) $admin->id,
                'subject' => 'Crop question',
                'content' => 'When should I harvest?',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true);

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
    }
}
