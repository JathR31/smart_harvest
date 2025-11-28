<?php
// Simple translation test without Laravel
header('Content-Type: application/json');

// Test 1: LibreTranslate
echo "Testing LibreTranslate:\n";
$ch = curl_init('https://libretranslate.com/translate');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'q' => 'Hello, how are you?',
    'source' => 'en',
    'target' => 'tl',
    'format' => 'text'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result1 = curl_exec($ch);
$httpCode1 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "LibreTranslate Response (HTTP $httpCode1):\n";
echo $result1 . "\n\n";

// Test 2: MyMemory
echo "Testing MyMemory:\n";
$text = urlencode('Hello, how are you?');
$url = "https://api.mymemory.translated.net/get?q=$text&langpair=en|tl";
$result2 = file_get_contents($url);

echo "MyMemory Response:\n";
echo $result2 . "\n\n";

// Test 3: Check supported languages
echo "Checking LibreTranslate supported languages:\n";
$languages = file_get_contents('https://libretranslate.com/languages');
echo $languages;
