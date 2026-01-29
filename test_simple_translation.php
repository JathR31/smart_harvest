<?php

require_once __DIR__ . '/app/Services/SimpleTranslationService.php';

use App\Services\SimpleTranslationService;

echo "Testing Simple Translation Service...\n\n";

// Test 1: Single translation
echo "Test 1: Single Translation\n";
echo "==========================\n";
$result = SimpleTranslationService::translate('Hello, welcome to SmartHarvest', 'tl', 'en');
echo "Translation Result:\n";
echo json_encode($result, JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Batch translation
echo "Test 2: Batch Translation\n";
echo "==========================\n";
$batchResult = SimpleTranslationService::batchTranslate([
    'Welcome to SmartHarvest',
    'Optimize Your Planting with Data',
    'Get Started Today'
], 'tl');

echo "Batch Translation Result:\n";
echo json_encode($batchResult, JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Supported languages
echo "Test 3: Supported Languages\n";
echo "==========================\n";
$languages = SimpleTranslationService::getSupportedLanguages();
echo "Supported Languages:\n";
echo json_encode($languages, JSON_PRETTY_PRINT) . "\n\n";

// Test 4: More translations
echo "Test 4: Additional Translations\n";
echo "===============================\n";
$testTexts = [
    'Dashboard',
    'Login',
    'Features',
    'Our Mission',
    'Team',
    'Weather Monitoring',
    'Crop Management'
];

foreach ($testTexts as $text) {
    $result = SimpleTranslationService::translate($text, 'tl', 'en');
    echo "$text -> " . $result['translatedText'] . " (" . $result['service'] . ")\n";
}

echo "\n✅ Simple translation service test completed!\n";