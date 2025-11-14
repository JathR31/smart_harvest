<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClimatePattern;

class ClimatePatternSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Creates historical climate data for Benguet municipalities (2020-2025)
     */
    public function run(): void
    {
        $municipalities = [
            'Atok', 'Baguio City', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 
            'Kabayan', 'Kapangan', 'Kibungan', 'La Trinidad', 'Mankayan', 
            'Sablan', 'Tuba', 'Tublay'
        ];
        
        $years = [2020, 2021, 2022, 2023, 2024, 2025];
        
        // Benguet climate characteristics:
        // - Cool climate due to high elevation
        // - Wet season: May-October (Southwest monsoon)
        // - Dry season: November-April (Northeast monsoon)
        // - Average temp: 15-20°C
        
        $weatherConditions = ['Sunny', 'Partly Cloudy', 'Cloudy', 'Rainy', 'Light Rain'];
        
        foreach ($municipalities as $municipality) {
            foreach ($years as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    // Temperature patterns (cooler in Dec-Feb, warmer in Mar-May)
                    $baseTemp = 17;
                    if ($month >= 12 || $month <= 2) {
                        $tempVariation = -2; // Cool season
                    } elseif ($month >= 3 && $month <= 5) {
                        $tempVariation = 2; // Warm season
                    } else {
                        $tempVariation = 0; // Wet season - moderate
                    }
                    
                    $avgTemp = $baseTemp + $tempVariation + (rand(-20, 20) / 10);
                    
                    // Rainfall patterns (wet May-Oct, dry Nov-Apr)
                    if ($month >= 5 && $month <= 10) {
                        // Wet season - high rainfall
                        $rainfall = rand(200, 600);
                        $weatherCondition = $weatherConditions[rand(3, 4)]; // Rainy/Light Rain
                    } elseif ($month >= 11 || $month <= 2) {
                        // Dry season - low rainfall
                        $rainfall = rand(10, 80);
                        $weatherCondition = $weatherConditions[rand(0, 1)]; // Sunny/Partly Cloudy
                    } else {
                        // Transition months
                        $rainfall = rand(80, 200);
                        $weatherCondition = $weatherConditions[rand(1, 3)]; // Mixed
                    }
                    
                    // Humidity (higher during wet season)
                    $humidity = ($month >= 5 && $month <= 10) 
                        ? rand(75, 90) 
                        : rand(60, 75);
                    
                    ClimatePattern::create([
                        'municipality' => $municipality,
                        'year' => $year,
                        'month' => $month,
                        'avg_temperature' => round($avgTemp, 2),
                        'min_temperature' => round($avgTemp - rand(3, 5), 2),
                        'max_temperature' => round($avgTemp + rand(3, 7), 2),
                        'rainfall' => round($rainfall, 2),
                        'humidity' => round($humidity + (rand(-5, 5)), 2),
                        'wind_speed' => round(rand(5, 15) + (rand(0, 10) / 10), 2),
                        'weather_condition' => $weatherCondition,
                    ]);
                }
            }
        }
        
        $totalRecords = count($municipalities) * count($years) * 12;
        $this->command->info("Created {$totalRecords} climate pattern records for {$municipalities[0]} and 13 other municipalities (2020-2025).");
    }
}
