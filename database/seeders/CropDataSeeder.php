<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CropDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Importing crop data from fulldataset.csv...');
        
        $csvFile = base_path('fulldataset.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error('fulldataset.csv not found!');
            return;
        }
        
        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header
        
        $batch = [];
        $count = 0;

        // Use an existing user for ownership; create a fallback user for fresh databases.
        $userId = DB::table('users')->value('id');
        if (!$userId) {
            $timestamp = now();
            $userId = DB::table('users')->insertGetId([
                'name' => 'SmartHarvest Seeder',
                'email' => 'seeder@smartharvest.local',
                'password' => Hash::make(bin2hex(random_bytes(16))),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            $this->command->warn("No users found. Created fallback user with ID {$userId}.");
        }
        
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 9) continue; // Skip incomplete rows
            
            // CSV Format: MUNICIPALITY,FARM TYPE,YEAR,MONTH,CROP,Area planted(ha),Area harvested(ha),Production(mt),Productivity(mt/ha)
            $year = intval($row[2] ?? date('Y'));
            $month = strtoupper(trim($row[3] ?? 'JAN'));
            
            // Convert month name to number
            $monthNum = date('m', strtotime($month . ' 1'));
            $plantingDate = "$year-$monthNum-01";
            $harvestDate = "$year-$monthNum-28"; // Approximate harvest date
            
            // Map CSV to actual table structure
            $batch[] = [
                'user_id' => $userId,
                'crop_type' => strtoupper(trim($row[4] ?? 'Unknown')),
                'variety' => null,
                'municipality' => strtoupper(trim($row[0] ?? 'Unknown')),
                'area_planted' => floatval($row[5] ?? 0),
                'yield_amount' => floatval($row[7] ?? 0), // Production (mt)
                'planting_date' => $plantingDate,
                'harvest_date' => $harvestDate,
                'status' => 'Harvested',
                'temperature' => null,
                'rainfall' => null,
                'humidity' => null,
                'notes' => "Farm Type: " . trim($row[1] ?? 'N/A') . ", Productivity: " . floatval($row[8] ?? 0) . " mt/ha, Area Harvested: " . floatval($row[6] ?? 0) . " ha",
                'validation_status' => 'Validated',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $count++;
            
            // Insert in batches of 1000
            if (count($batch) >= 1000) {
                DB::table('crop_data')->insert($batch);
                $this->command->info("Imported $count rows...");
                $batch = [];
            }
        }
        
        // Insert remaining rows
        if (count($batch) > 0) {
            DB::table('crop_data')->insert($batch);
        }
        
        fclose($file);
        
        $this->command->info("✅ Successfully imported $count crop data records!");
    }
}

