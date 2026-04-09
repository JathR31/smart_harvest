<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketPrice;
use App\Models\User;

class MarketPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds market prices for crops from the ML dataset
     */
    public function run(): void
    {
        // Get a DA Officer user to be the creator (role is 'DA Admin' in the database)
        $daOfficer = User::where('role', 'DA Admin')->first();
        if (!$daOfficer) {
            $daOfficer = User::where('role', 'Admin')->first();
        }
        if (!$daOfficer) {
            $daOfficer = User::first();
        }
        $creatorId = $daOfficer ? $daOfficer->id : null;

        if (!$creatorId) {
            $this->command->error('No user found for created_by. Seed at least one user first.');
            return;
        }

        $priceDate = now()->toDateString();

        // Crops from the ML dataset - prices to be inputted by DA Officer
        $crops = [
            ['crop_name' => 'CABBAGE', 'variety' => 'Highland Cabbage', 'price_per_kg' => 25.00],
            ['crop_name' => 'CHINESE CABBAGE', 'variety' => 'Pechay Baguio', 'price_per_kg' => 30.00],
            ['crop_name' => 'LETTUCE', 'variety' => 'Iceberg Lettuce', 'price_per_kg' => 35.00],
            ['crop_name' => 'CAULIFLOWER', 'variety' => 'White Cauliflower', 'price_per_kg' => 45.00],
            ['crop_name' => 'BROCCOLI', 'variety' => 'Green Broccoli', 'price_per_kg' => 60.00],
            ['crop_name' => 'SNAP BEANS', 'variety' => 'Baguio Beans', 'price_per_kg' => 55.00],
            ['crop_name' => 'GARDEN PEAS', 'variety' => 'Sweet Peas', 'price_per_kg' => 50.00],
            ['crop_name' => 'SWEET PEPPER', 'variety' => 'Bell Pepper', 'price_per_kg' => 80.00],
            ['crop_name' => 'WHITE POTATO', 'variety' => 'Benguet Potato', 'price_per_kg' => 28.00],
            ['crop_name' => 'CARROTS', 'variety' => 'Orange Carrots', 'price_per_kg' => 30.00],
        ];

        foreach ($crops as $crop) {
            MarketPrice::updateOrCreate(
                ['crop_name' => $crop['crop_name']],
                [
                    'variety' => $crop['variety'],
                    'price_per_kg' => $crop['price_per_kg'],
                    'previous_price' => null,
                    'price_trend' => 'stable',
                    'demand_level' => 'moderate',
                    'market_location' => 'La Trinidad Trading Post',
                    'created_by' => $creatorId,
                    'price_date' => $priceDate,
                    'is_active' => true,
                    'notes' => 'Price to be set by DA Officer',
                ]
            );
        }

        $this->command->info('ML dataset crops seeded - ' . count($crops) . ' crops ready for price input by DA Officer!');
    }
}
