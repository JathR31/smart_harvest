<?php

require __DIR__.'/vendor/autoload.php';

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║              DIRECT API CONNECTION TEST                                   ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

$apiKey = 'sk-0df679fa423fe9b837ad7df1';
$apiUrl = 'https://api.smsapi.ph/v1/balance';

echo "Testing direct HTTPS connection to SMS API Philippines...\n\n";

echo "1. Testing with Laravel Http (withoutVerifying)...\n";
echo str_repeat('-', 79) . "\n";

try {
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->timeout(15)->get($apiUrl);
    
    echo "Status: {$response->status()}\n";
    echo "Body: {$response->body()}\n\n";
    
    if ($response->successful()) {
        echo "✅ SUCCESS with withoutVerifying!\n\n";
    } else {
        echo "⚠ Request completed but not successful\n\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
}

echo "2. Testing with Laravel Http (with SSL verification)...\n";
echo str_repeat('-', 79) . "\n";

try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Accept' => 'application/json',
    ])->timeout(15)->get($apiUrl);
    
    echo "Status: {$response->status()}\n";
    echo "Body: {$response->body()}\n\n";
    
    if ($response->successful()) {
        echo "✅ SUCCESS with SSL verification!\n\n";
    } else {
        echo "⚠ Request completed but not successful\n\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n\n";
}

echo "3. Testing with raw cURL...\n";
echo str_repeat('-', 79) . "\n";

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);
fclose($verbose);
curl_close($ch);

if ($error) {
    echo "❌ cURL Error: {$error}\n";
    echo "\nVerbose log:\n{$verboseLog}\n\n";
} else {
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: {$response}\n\n";
    
    if ($httpCode == 200) {
        echo "✅ SUCCESS with raw cURL!\n\n";
    }
}

echo "4. PHP Configuration Check...\n";
echo str_repeat('-', 79) . "\n";
echo "curl.cainfo: " . ini_get('curl.cainfo') . "\n";
echo "openssl.cafile: " . ini_get('openssl.cafile') . "\n";
echo "allow_url_fopen: " . ini_get('allow_url_fopen') . "\n";
echo "OpenSSL version: " . OPENSSL_VERSION_TEXT . "\n\n";

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                           DIAGNOSIS                                       ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "If all tests fail:\n";
echo "  - Check firewall/antivirus settings\n";
echo "  - Try accessing https://api.smsapi.ph in your browser\n";
echo "  - Check if your ISP blocks certain API endpoints\n\n";

echo "If withoutVerifying works but SSL verification fails:\n";
echo "  - The cacert.pem file may need updating\n";
echo "  - Try downloading fresh cert from: https://curl.se/docs/caextract.html\n\n";
