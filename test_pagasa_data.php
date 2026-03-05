<?php

// Test script to check PAGASA weather data
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PAGASA Weather Data Test ===\n\n";

// Check ENSO Status
echo "1. ENSO ALERT STATUS:\n";
$enso = App\Models\EnsoAlert::getCurrentStatus();
if ($enso) {
    echo "   Status: " . strtoupper(str_replace('_', ' ', $enso->status)) . "\n";
    echo "   Description: " . substr($enso->description, 0, 150) . "...\n";
    echo "   Updated: " . $enso->updated_date . "\n";
} else {
    echo "   No ENSO data found\n";
}

echo "\n2. SOIL MOISTURE DATA (Sample 10):\n";
$soilData = App\Models\SoilMoistureData::getCurrentData()->take(10);
foreach ($soilData as $soil) {
    echo "   - {$soil->municipality}: " . strtoupper($soil->condition) . "\n";
}

echo "\n3. WEATHER FORECASTS:\n";
$forecasts = App\Models\WeatherForecast::getCurrentForecasts();
foreach ($forecasts as $forecast) {
    echo "   - {$forecast->region}\n";
    echo "     Temp: {$forecast->temp_low_range} - {$forecast->temp_high_range}°C\n";
    echo "     Humidity: {$forecast->humidity_range}%\n";
    echo "     Rainfall: {$forecast->rainfall_range}mm\n";
}

echo "\n4. FARMING ADVISORIES:\n";
$advisories = App\Models\FarmingAdvisory::getActiveAdvisories()->take(3);
foreach ($advisories as $advisory) {
    echo "   - [{$advisory->severity}] {$advisory->title}\n";
}

echo "\n5. GALE WARNINGS:\n";
$warnings = App\Models\GaleWarning::getActiveWarnings();
foreach ($warnings as $warning) {
    echo "   - {$warning->area} ({$warning->severity})\n";
    echo "     " . substr($warning->description, 0, 100) . "...\n";
}

echo "\n=== DATA COUNTS ===\n";
echo "Weather Forecasts: " . App\Models\WeatherForecast::count() . "\n";
echo "Soil Moisture Data: " . App\Models\SoilMoistureData::count() . "\n";
echo "Farming Advisories: " . App\Models\FarmingAdvisory::count() . "\n";
echo "ENSO Alerts: " . App\Models\EnsoAlert::count() . "\n";
echo "Gale Warnings: " . App\Models\GaleWarning::count() . "\n";

echo "\n=== Test Complete ===\n";
