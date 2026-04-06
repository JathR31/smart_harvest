<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\TranslationService;

echo "Testing Translation Service Directly...\n\n";

// Test 1: Direct service test
echo "Test 1: Direct Translation Service\n";
echo "=====================================\n";

try {
    $translationService = new TranslationService();
    
    // Test translation
    $result = $translationService->translate('Hello, welcome to SmartHarvest', 'tl', 'en');
    echo "Translation Result:\n";
    echo json_encode($result, JSON_PRETTY_PRINT) . "\n\n";
    
    // Test batch translation
    $batchResult = $translationService->batchTranslate([
        'Welcome to SmartHarvest',
        'Optimize Your Planting with Data',
        'Get Started Today'
    ], 'tl');
    
    echo "Batch Translation Result:\n";
    echo json_encode($batchResult, JSON_PRETTY_PRINT) . "\n\n";
    
    // Test supported languages
    $languages = $translationService->getSupportedLanguages();
    echo "Supported Languages:\n";
    echo json_encode($languages, JSON_PRETTY_PRINT) . "\n\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "✅ Direct service test completed!\n";