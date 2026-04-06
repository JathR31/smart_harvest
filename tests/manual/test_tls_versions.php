<?php

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║              TLS VERSION COMPATIBILITY TEST                               ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

$apiUrl = 'https://api.smsapi.ph/v1/balance';
$apiKey = 'sk-0df679fa423fe9b837ad7df1';

$tlsVersions = [
    'TLS 1.0' => CURL_SSLVERSION_TLSv1_0,
    'TLS 1.1' => CURL_SSLVERSION_TLSv1_1,
    'TLS 1.2' => CURL_SSLVERSION_TLSv1_2,
    'TLS 1.3' => CURL_SSLVERSION_TLSv1_3,
    'Default' => CURL_SSLVERSION_DEFAULT,
];

echo "Testing different TLS versions...\n\n";

foreach ($tlsVersions as $name => $version) {
    echo "Testing {$name}... ";
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, $version);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Failed: " . substr($error, 0, 50) . "...\n";
    } elseif ($httpCode == 200) {
        echo "✅ SUCCESS!\n";
        echo "  Response: {$response}\n";
        break;
    } else {
        echo "⚠ HTTP {$httpCode}\n";
    }
}

echo "\n";
echo str_repeat('-', 79) . "\n";
echo "Testing simple HTTPS connection to Google (to verify SSL works at all)...\n\n";

$ch = curl_init('https://www.google.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Google test failed: {$error}\n";
    echo "   Your SSL/network configuration has issues.\n\n";
} elseif ($httpCode == 200) {
    echo "✅ Google test successful!\n";
    echo "   SSL works, but api.smsapi.ph specifically has issues.\n\n";
}

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                             SOLUTIONS                                     ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "The issue is likely:\n";
echo "1. ⚠ Firewall/Antivirus blocking api.smsapi.ph\n";
echo "   Solution: Temporarily disable firewall/antivirus and test\n\n";

echo "2. ⚠ ISP/Network blocking the API\n";
echo "   Solution: Try using mobile hotspot or different network\n\n";

echo "3. ⚠ The API server itself has SSL configuration issues\n";
echo "   Solution: Contact SMS API Philippines support\n\n";

echo "4. ✅ RECOMMENDED: Use Semaphore SMS instead\n";
echo "   - Works better on Windows/XAMPP\n";
echo "   - Get API key from: https://semaphore.co\n";
echo "   - More reliable for local development\n\n";

echo "OR keep using SIMULATION MODE for development:\n";
echo "   - Set SMS_SIMULATION_MODE=true in .env\n";
echo "   - All SMS logged to storage/logs/laravel.log\n";
echo "   - Perfect for testing without actual SMS costs\n\n";
