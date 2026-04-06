<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== WEATHER FORECASTS (CLEANED & FILTERED) ===\n";
$forecasts = \App\Models\WeatherForecast::orderBy('created_at', 'desc')->get();
echo "Total forecasts: " . $forecasts->count() . "\n\n";

foreach ($forecasts as $forecast) {
    echo "Region: " . $forecast->region . "\n";
    echo "Synopsis (cleaned):\n";
    echo substr($forecast->synopsis, 0, 200) . "...\n";
    echo "Temperature: " . $forecast->temperature_range . "\n";
    echo "Humidity: " . $forecast->humidity_range . "\n";
    echo "Rainfall: " . $forecast->rainfall_expectation . "\n";
    echo str_repeat("-", 60) . "\n";
}

echo "\n=== SOIL MOISTURE DATA (BENGUET ONLY) ===\n";
$soil = \App\Models\SoilMoistureData::orderBy('created_at', 'desc')->get();
echo "Total records: " . $soil->count() . "\n\n";

foreach ($soil as $data) {
    echo "Municipality: " . $data->municipality . "\n";
    echo "Province: " . $data->province . "\n";
    echo "Condition: " . $data->condition . "\n";
    echo str_repeat("-", 40) . "\n";
}

echo "\n=== FARMING ADVISORIES (CLEANED) ===\n";
$advisories = \App\Models\FarmingAdvisory::orderBy('created_at', 'desc')->take(3)->get();
echo "Total advisories: " . \App\Models\FarmingAdvisory::count() . " (showing first 3)\n\n";

foreach ($advisories->take(3) as $advisory) {
    echo "Category: " . $advisory->category . "\n";
    echo "Title: " . $advisory->title . "\n";
    echo "Content (first 150 chars):\n";
    echo substr($advisory->content, 0, 150) . "...\n";
    echo str_repeat("-", 60) . "\n";
}
