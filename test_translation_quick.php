<?php

// Quick test for translation API

$url = "http://localhost/dashboard/smart_harvest/public/api/translate";

$data = [
    'text' => 'Hello, welcome to SmartHarvest',
    'target_language' => 'tl',
    'source_language' => 'en'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "Response from Translation API:\n";
echo $result . "\n\n";

$decoded = json_decode($result, true);
echo "Status: " . ($decoded['status'] ?? 'unknown') . "\n";
echo "Translated Text: " . ($decoded['translatedText'] ?? 'N/A') . "\n";
echo "Service Used: " . ($decoded['service'] ?? 'N/A') . "\n";
