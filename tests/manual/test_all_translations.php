<?php

// Test all supported languages

$url = "http://localhost/dashboard/smart_harvest/public/api/translate";

$tests = [
    ['text' => 'Hello, welcome to SmartHarvest', 'target' => 'tl', 'name' => 'English to Tagalog'],
    ['text' => 'Hello, welcome to SmartHarvest', 'target' => 'ilo', 'name' => 'English to Ilocano'],
    ['text' => 'Good morning, farmer', 'target' => 'tl', 'name' => 'English to Tagalog (2)'],
    ['text' => 'Good morning, farmer', 'target' => 'ilo', 'name' => 'English to Ilocano (2)'],
];

echo "===== TRANSLATION API TESTS =====\n\n";

foreach ($tests as $test) {
    $data = [
        'text' => $test['text'],
        'target_language' => $test['target'],
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
    $decoded = json_decode($result, true);

    echo "Test: {$test['name']}\n";
    echo "Original: {$test['text']}\n";
    echo "Translated: " . ($decoded['translatedText'] ?? 'ERROR') . "\n";
    echo "Service: " . ($decoded['service'] ?? 'N/A') . "\n";
    echo "Status: " . ($decoded['status'] ?? 'unknown') . "\n";
    echo str_repeat("-", 60) . "\n\n";
}

echo "\n===== BATCH TRANSLATION TEST =====\n\n";

$batchUrl = "http://localhost/dashboard/smart_harvest/public/api/translate/batch";
$batchData = [
    'texts' => [
        'Dashboard',
        'Planting Schedule',
        'Yield Analysis',
        'Weather Forecast'
    ],
    'target_language' => 'tl'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($batchData)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($batchUrl, false, $context);
$decoded = json_decode($result, true);

if ($decoded && isset($decoded['translations'])) {
    foreach ($decoded['translations'] as $index => $trans) {
        echo $batchData['texts'][$index] . " → " . ($trans['translatedText'] ?? 'ERROR') . "\n";
    }
}

echo "\n✅ ALL TESTS COMPLETED\n";
