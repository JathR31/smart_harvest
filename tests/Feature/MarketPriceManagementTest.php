<?php

namespace Tests\Feature;

use App\Models\MarketPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketPriceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_market_price_list_includes_the_record_id_needed_for_editing(): void
    {
        $price = $this->createMarketPrice();

        $response = $this->getJson('/api/market-prices');

        $response->assertOk()
            ->assertJsonFragment(['id' => $price->id]);
    }

    public function test_authorized_admin_can_update_a_market_price(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $price = $this->createMarketPrice([
            'created_by' => $admin->id,
            'price_per_kg' => 25,
            'price_date' => '2026-08-01',
        ]);

        $this->actingAs($admin)
            ->putJson("/api/market-prices/{$price->id}", [
                'price_per_kg' => 30,
                'price_date' => '2026-08-02',
                'demand_level' => 'high',
                'market_location' => 'La Trinidad Trading Post',
            ])
            ->assertOk()
            ->assertJsonPath('id', $price->id)
            ->assertJsonPath('price_trend', 'up');

        $this->assertDatabaseHas('market_prices', [
            'id' => $price->id,
            'price_per_kg' => 30,
            'previous_price' => 25,
            'demand_level' => 'high',
        ]);
    }

    private function createMarketPrice(array $attributes = []): MarketPrice
    {
        $creator = User::factory()->create(['role' => 'Admin']);

        return MarketPrice::create(array_merge([
            'created_by' => $creator->id,
            'crop_name' => 'Carrots',
            'price_per_kg' => 25,
            'price_trend' => 'stable',
            'market_location' => 'La Trinidad Trading Post',
            'demand_level' => 'moderate',
            'price_date' => '2026-08-01',
            'is_active' => true,
        ], $attributes));
    }
}
