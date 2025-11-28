<?php

// Direct translation test
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\TranslationService;

$service = app(TranslationService::class);

echo "Testing TranslationService...\n\n";

// Test 1: Single translation
echo "Test 1: Single translation\n";
$result = $service->translate('Hello, how are you?', 'tl', 'en');
echo "Input: Hello, how are you?\n";
echo "Output: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Batch translation
echo "Test 2: Batch translation\n";
$texts = [
    'Welcome to SmartHarvest',
    'Optimize Your Planting with Data',
    'Get Started'
];
$results = $service->batchTranslate($texts, 'tl');
echo "Results:\n" . json_encode($results, JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Test API endpoint simulation
echo "Test 3: API endpoint simulation\n";
$texts = ['Hello', 'Welcome', 'Get Started'];
$results = $service->batchTranslate($texts, 'tl');
$response = [
    'status' => 'success',
    'translations' => $results
];
echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
