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

        // Crops from the ML dataset - prices to be inputted by DA Officer
        $crops = [
            ['crop_name' => 'CABBAGE', 'variety' => 'Highland Cabbage'],
            ['crop_name' => 'CHINESE CABBAGE', 'variety' => 'Pechay Baguio'],
            ['crop_name' => 'LETTUCE', 'variety' => 'Iceberg Lettuce'],
            ['crop_name' => 'CAULIFLOWER', 'variety' => 'White Cauliflower'],
            ['crop_name' => 'BROCCOLI', 'variety' => 'Green Broccoli'],
            ['crop_name' => 'SNAP BEANS', 'variety' => 'Baguio Beans'],
            ['crop_name' => 'GARDEN PEAS', 'variety' => 'Sweet Peas'],
            ['crop_name' => 'SWEET PEPPER', 'variety' => 'Bell Pepper'],
            ['crop_name' => 'WHITE POTATO', 'variety' => 'Benguet Potato'],
            ['crop_name' => 'CARROTS', 'variety' => 'Orange Carrots'],
        ];

        foreach ($crops as $crop) {
            MarketPrice::updateOrCreate(
                ['crop_name' => $crop['crop_name']],
                [
                    'variety' => $crop['variety'],
                    'price_per_kg' => null,
                    'previous_price' => null,
                    'price_trend' => 'stable',
                    'demand_level' => 'moderate',
                    'market_location' => 'La Trinidad Trading Post',
                    'created_by' => $creatorId,
                    'price_date' => null,
                    'is_active' => true,
                    'notes' => 'Price to be set by DA Officer',
                ]
            );
        }

        $this->command->info('ML dataset crops seeded - ' . count($crops) . ' crops ready for price input by DA Officer!');
    }
}
