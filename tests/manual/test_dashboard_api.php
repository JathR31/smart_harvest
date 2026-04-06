<?php
// Test dashboard API
require __DIR__.'/bootstrap/app.php';

$app = $app ?? \Illuminate\Foundation\Application::getInstance();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\CropData;
use App\Models\ClimatePattern;

echo "===== TESTING DASHBOARD DATA =====\n";
echo "Farmers: " . User::where('role', 'Farmer')->count() . "\n";
echo "CropData: " . CropData::count() . "\n";
echo "ClimatePatterns: " . ClimatePattern::count() . "\n";

// Test the actual API controller
$controller = new \App\Http\Controllers\Api\DAOfficerApiController();
$response = $controller->getDashboardStats();
$data = $response->getData(true);

echo "\n===== API RESPONSE =====\n";
echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
