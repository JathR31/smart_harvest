<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

echo "Testing ML API Direct Connection...\n\n";

$mlService = new \App\Services\MLApiService();

// Test 1: Health Check
echo "1. ML API Health Check:\n";
$health = $mlService->checkHealth();
echo "   Status: " . ($health['status'] ?? 'unknown') . "\n";
echo "   Message: " . ($health['message'] ?? 'N/A') . "\n\n";

// Test 2: Get Top Crops (try different municipalities)
$testMunicipalities = ['LATRINIDAD', 'LA TRINIDAD', 'Atok', 'ATOK', 'Buguias', 'BUGUIAS'];

foreach ($testMunicipalities as $mun) {
    echo "2. Testing Top Crops for: $mun\n";
    $result = $mlService->getTopCrops(['MUNICIPALITY' => $mun]);
    
    if ($result['status'] === 'success') {
        echo "   ✅ SUCCESS!\n";
        $data = $result['data'];
        
        if (isset($data['predicted_top5']['crops'])) {
            $crops = $data['predicted_top5']['crops'];
            echo "   Found " . count($crops) . " crops:\n";
            foreach (array_slice($crops, 0, 3) as $crop) {
                echo "     - " . ($crop['crop'] ?? 'Unknown') . "\n";
            }
        }
        break; // Stop when we find working municipality
    } else {
        echo "   ❌ Failed: " . ($result['message'] ?? 'Unknown error') . "\n";
    }
    echo "\n";
}

echo "\n3. Testing Yield Prediction:\n";
$yieldResult = $mlService->predictYield([
    'MUNICIPALITY' => 'LATRINIDAD',
    'CROP_NAME' => 'CABBAGE',
    'VARIETY' => 'SCORPIO'
]);

if ($yieldResult['status'] === 'success') {
    echo "   ✅ Yield prediction successful\n";
    $prediction = $yieldResult['data']['prediction'] ?? [];
    echo "   Predicted Yield: " . ($prediction['predicted_yield'] ?? 'N/A') . " MT/ha\n";
} else {
    echo "   ❌ Failed: " . ($yieldResult['message'] ?? 'Unknown') . "\n";
}

echo "\n=================\n";
echo "If you see ✅ SUCCESS above, the ML API is working!\n";
echo "=================\n";
