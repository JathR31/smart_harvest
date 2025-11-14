<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CropData;
use App\Models\User;
use Carbon\Carbon;

class CropDataSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run SampleUsersSeeder first.');
            return;
        }

        $municipalities = ['Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
                          'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
                          'Sablan', 'Tuba', 'Tublay'];
        
        $crops = [
            ['type' => 'Cabbage', 'varieties' => ['Scorpio', 'Green Coronet', 'KY Cross']],
            ['type' => 'Carrot', 'varieties' => ['Kuroda', 'Nantes', 'Chantenay']],
            ['type' => 'Potato', 'varieties' => ['Granola', 'Solara', 'Atlantic']],
            ['type' => 'Lettuce', 'varieties' => ['Grand Rapids', 'Buttercrunch', 'Romaine']],
            ['type' => 'Tomato', 'varieties' => ['Diamante', 'Apollo', 'Lovin']],
        ];

        $statuses = ['Planning', 'Planted', 'Growing', 'Harvested'];
        $validationStatuses = ['Pending', 'Validated', 'Flagged'];

        // Create 50 diverse crop data records
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $crop = $crops[array_rand($crops)];
            $status = $statuses[array_rand($statuses)];
            $municipality = $municipalities[array_rand($municipalities)];
            
            $plantingDate = Carbon::now()->subDays(rand(1, 180));
            $harvestDate = null;
            
            if ($status === 'Harvested') {
                $harvestDate = $plantingDate->copy()->addDays(rand(60, 120));
            }

            CropData::create([
                'user_id' => $user->id,
                'crop_type' => $crop['type'],
                'variety' => $crop['varieties'][array_rand($crop['varieties'])],
                'municipality' => $municipality,
                'area_planted' => rand(5, 200) / 10, // 0.5 to 20 hectares
                'yield_amount' => $status === 'Harvested' ? rand(1000, 15000) : null,
                'planting_date' => $plantingDate,
                'harvest_date' => $harvestDate,
                'status' => $status,
                'temperature' => rand(150, 280) / 10, // 15°C to 28°C
                'rainfall' => rand(100, 500), // 100mm to 500mm
                'humidity' => rand(60, 90), // 60% to 90%
                'notes' => rand(0, 1) ? 'Normal growth conditions' : null,
                'validation_status' => $validationStatuses[array_rand($validationStatuses)],
            ]);
        }

        // Create a few records with data quality issues for alerts
        $flaggedUser = $users->random();
        
        // Unusual high yield - should trigger validation
        CropData::create([
            'user_id' => $flaggedUser->id,
            'crop_type' => 'Cabbage',
            'variety' => 'Scorpio',
            'municipality' => 'La Trinidad',
            'area_planted' => 2.5,
            'yield_amount' => 35000, // Unusually high
            'planting_date' => Carbon::now()->subDays(90),
            'harvest_date' => Carbon::now()->subDays(10),
            'status' => 'Harvested',
            'temperature' => 22,
            'rainfall' => 250,
            'humidity' => 75,
            'notes' => 'Exceptional yield reported',
            'validation_status' => 'Flagged',
        ]);

        // Missing data - should trigger alert
        CropData::create([
            'user_id' => $flaggedUser->id,
            'crop_type' => 'Carrot',
            'variety' => 'Kuroda',
            'municipality' => 'Atok',
            'area_planted' => 1.5,
            'yield_amount' => null,
            'planting_date' => Carbon::now()->subDays(150),
            'harvest_date' => null,
            'status' => 'Growing',
            'temperature' => null, // Missing
            'rainfall' => null, // Missing
            'humidity' => null, // Missing
            'notes' => 'Incomplete data submission',
            'validation_status' => 'Flagged',
        ]);

        $this->command->info('Created 52 crop data records with diverse data and 2 flagged for validation.');
    }
}
