<?php

// Test Translation API endpoint via XAMPP Apache
echo "Testing Translation API via XAMPP Apache...\n\n";

// Base URL for XAMPP
$baseUrl = 'http://localhost/dashboard/smart_harvest/public';

// Test 1: Single translation
echo "Test 1: Single Translation\n";
echo "==========================\n";
$ch = curl_init($baseUrl . '/api/translate');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'text' => 'Hello, welcome to SmartHarvest',
    'target_language' => 'tl',
    'source_language' => 'en'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
if ($error) {
    echo "CURL Error: $error\n";
}
echo "Response:\n";
echo json_encode(json_decode($result), JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Batch translation
echo "Test 2: Batch Translation\n";
echo "==========================\n";
$ch = curl_init($baseUrl . '/api/translate/batch');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'texts' => [
        'Welcome to SmartHarvest',
        'Optimize Your Planting with Data',
        'Get Started Today'
    ],
    'target_language' => 'tl'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
if ($error) {
    echo "CURL Error: $error\n";
}
echo "Response:\n";
echo json_encode(json_decode($result), JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Get supported languages
echo "Test 3: Supported Languages\n";
echo "==========================\n";
$ch = curl_init($baseUrl . '/api/translate/languages');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status: $httpCode\n";
if ($error) {
    echo "CURL Error: $error\n";
}
echo "Response:\n";
echo json_encode(json_decode($result), JSON_PRETTY_PRINT) . "\n\n";

echo "✅ All tests completed!\n";
