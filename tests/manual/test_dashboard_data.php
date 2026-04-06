<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PAGASA Dashboard Data Check ===\n\n";

// Simulate what the controller does
$service = new App\Services\PagasaWeatherService();
$data = $service->getDashboardSummary();

echo "1. FORECASTS:\n";
echo "   Count: " . $data['forecasts']->count() . "\n";
foreach ($data['forecasts'] as $forecast) {
    echo "   - Region: {$forecast->region}\n";
    echo "     Temp: {$forecast->temp_low_range} - {$forecast->temp_high_range}°C\n";
    echo "     Date: {$forecast->forecast_date}\n";
    echo "     Valid until: {$forecast->valid_until}\n\n";
}

echo "2. SOIL MOISTURE:\n";
echo "   Wet: {$data['soil_moisture']['wet']}\n";
echo "   Moist: {$data['soil_moisture']['moist']}\n";
echo "   Dry: {$data['soil_moisture']['dry']}\n\n";

echo "3. ADVISORIES:\n";
echo "   Count: " . $data['advisories']->count() . "\n";
foreach ($data['advisories'] as $advisory) {
    echo "   - [{$advisory->severity}] {$advisory->title}\n";
}

echo "\n4. ENSO:\n";
if ($data['enso']) {
    echo "   Status: {$data['enso']->status}\n";
    echo "   Description: " . substr($data['enso']->description, 0, 100) . "...\n";
} else {
    echo "   No ENSO data\n";
}

echo "\n5. WARNINGS:\n";
echo "   Count: " . $data['warnings']->count() . "\n";
foreach ($data['warnings'] as $warning) {
    echo "   - {$warning->area} ({$warning->severity})\n";
}

echo "\n6. LAST UPDATE:\n";
echo "   " . ($data['last_update'] ?? 'Never') . "\n";

echo "\n=== End ===\n";
