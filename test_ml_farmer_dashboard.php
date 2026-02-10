<?php
// Quick test for ML Farmer Dashboard API
require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Route;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test the ML Yield Analysis API endpoint
echo "Testing ML Yield Analysis API for Farmer Dashboard...\n\n";

$municipality = 'La Trinidad';
$year = 2025;

echo "Municipality: $municipality\n";
echo "Year: $year\n\n";

// Make a fake request
$request = Illuminate\Http\Request::create(
    "/api/ml/yield/analysis?municipality=$municipality&year=$year",
    'GET'
);

try {
    $response = $kernel->handle($request);
    $content = $response->getContent();
    $data = json_decode($content, true);
    
    echo "Response Status: " . $response->getStatusCode() . "\n\n";
    
    if (isset($data['stats'])) {
        echo "📊 Stats:\n";
        echo "  - Expected Harvest: " . ($data['stats']['total_production'] ?? 'N/A') . " MT\n";
        echo "  - Average Yield: " . ($data['stats']['avg_yield'] ?? 'N/A') . " MT/ha\n";
        echo "  - Best Crop: " . ($data['stats']['best_crop'] ?? 'N/A') . "\n";
        echo "  - Total Area: " . ($data['stats']['total_area'] ?? 'N/A') . " ha\n\n";
    }
    
    echo "🔌 ML Status: " . ($data['ml_status'] ?? 'unknown') . "\n";
    echo "✅ ML API Connected: " . ($data['ml_api_connected'] ? 'YES' : 'NO') . "\n\n";
    
    if (isset($data['crops']) && count($data['crops']) > 0) {
        echo "🌾 Top " . count($data['crops']) . " Crops:\n";
        foreach ($data['crops'] as $idx => $crop) {
            echo "  " . ($idx + 1) . ". " . ($crop['crop'] ?? 'Unknown') . 
                 " - " . ($crop['yield_prediction'] ?? 'N/A') . " MT/ha\n";
        }
    } else {
        echo "⚠️ No crops data returned\n";
    }
    
    echo "\n";
    
    if (isset($data['comparison']) && count($data['comparison']) > 0) {
        echo "📈 Yearly Comparison Data: " . count($data['comparison']) . " years\n";
        foreach (array_slice($data['comparison'], -3) as $yearData) {
            echo "  - " . ($yearData['year'] ?? 'N/A') . 
                 ": Predicted=" . ($yearData['predicted'] ?? 'N/A') . 
                 ", Actual=" . ($yearData['actual'] ?? 'N/A') . "\n";
        }
    } else {
        echo "⚠️ No comparison data\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=================\n";
echo "TEST COMPLETE\n";
echo "=================\n";
