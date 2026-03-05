<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FINAL BENGUET WEATHER DATA ===\n\n";

$forecast = \App\Models\WeatherForecast::first();

if ($forecast) {
    echo "Region: " . $forecast->region . "\n\n";
    echo "Synopsis:\n" . $forecast->synopsis . "\n\n";
    echo "Weather Condition:\n" . $forecast->weather_condition . "\n\n";
    echo "Wind Condition:\n" . $forecast->wind_condition . "\n\n";
    echo "Temperature (Lowland): " . $forecast->temp_high_range . "\n";
    echo "Temperature (Upland): " . $forecast->temp_low_range . "\n";
    echo "Humidity: " . $forecast->humidity_range . "\n";
    echo "Leaf Wetness (Rainfall): " . $forecast->rainfall_range . "\n";
    echo "Valid Until: " . $forecast->valid_until . "\n\n";
} else {
    echo "No forecast found\n";
}

echo "=== FARMING ADVISORIES ===\n";
$advisories = \App\Models\FarmingAdvisory::all();
echo "Total: " . $advisories->count() . "\n\n";

foreach ($advisories as $i => $advisory) {
    echo ($i + 1) . ". " . $advisory->title . "\n";
    echo "   " . $advisory->description . "\n\n";
}
