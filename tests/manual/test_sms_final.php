<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║          SMS API PHILIPPINES - FINAL CONNECTION TEST                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "Testing with ACTUAL service methods (SSL fix applied)...\n\n";

// Get farmer with phone
$farmer = \App\Models\User::where('role', 'farmer')
    ->where(function($q) {
        $q->whereNotNull('phone')->orWhereNotNull('phone_number');
    })
    ->first();

if (!$farmer) {
    echo "❌ No farmer with phone number found.\n";
    echo "   Please add a phone number to a farmer account first.\n";
    exit(1);
}

$phone = $farmer->phone ?? $farmer->phone_number;

echo "Found farmer: {$farmer->name}\n";
echo "Phone number: {$phone}\n\n";

echo str_repeat('-', 79) . "\n";
echo "SENDING TEST SMS...\n";
echo str_repeat('-', 79) . "\n\n";

try {
    $smsService = app(\App\Services\SMSApiPhilippinesService::class);
    
    $message = "TEST from SmartHarvest - " . date('M d, Y h:i:s A');
    
    echo "Message: {$message}\n";
    echo "Recipient: {$phone}\n";
    echo "Sending...\n\n";
    
    $result = $smsService->sendMessage($phone, $message, 'SmartHarvest');
    
    if ($result['success']) {
        echo "✅ SUCCESS! SMS SENT!\n\n";
        echo "Message: {$result['message']}\n";
        
        if (isset($result['data'])) {
            echo "\nAPI Response:\n";
            echo json_encode($result['data'], JSON_PRETTY_PRINT) . "\n";
        }
        
        echo "\n";
        echo "╔════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                        ✅ SMS SENT SUCCESSFULLY!                        ║\n";
        echo "╚════════════════════════════════════════════════════════════════════════╝\n";
        echo "\nCheck your phone: {$phone}\n";
        echo "You should receive the text message shortly!\n\n";
        
    } else {
        echo "❌ FAILED TO SEND SMS\n\n";
        echo "Error: {$result['message']}\n\n";
        
        // Check logs
        echo "Checking Laravel logs for details...\n";
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            $logContents = file_get_contents($logFile);
            $lines = explode("\n", $logContents);
            $lastLines = array_slice($lines, -30);
            $smsLines = array_filter($lastLines, function($line) {
                return stripos($line, 'sms') !== false;
            });
            
            if (count($smsLines) > 0) {
                echo "\nRecent SMS logs:\n";
                echo implode("\n", $smsLines) . "\n";
            }
        }
    }
    
} catch (\Exception $e) {
    echo "❌ EXCEPTION OCCURRED\n\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    
    if (strpos($e->getMessage(), 'cURL error 35') !== false) {
        echo "╔════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                     SSL CONNECTION ISSUE DETECTED                      ║\n";
        echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";
        
        echo "This is likely caused by:\n";
        echo "1. Firewall or antivirus blocking the connection\n";
        echo "2. SSL certificate verification issues on XAMPP/Windows\n";
        echo "3. Network connectivity issues\n\n";
        
        echo "SOLUTION:\n";
        echo "1. Temporarily disable antivirus/firewall\n";
        echo "2. Or use a different SMS API (like Semaphore via HTTP)\n";
        echo "3. Or configure SSL certificates properly in php.ini\n\n";
        
        echo "Alternative: Would you like to use Semaphore SMS instead?\n";
        echo "Semaphore works better on Windows/XAMPP environments.\n\n";
    } elseif (strpos($e->getMessage(), '401') !== false) {
        echo "╔════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                      API KEY AUTHENTICATION ERROR                      ║\n";
        echo "╚════════════════════════════════════════════════════════════════════════╝\n\n";
        
        echo "Your API key might be invalid or expired.\n";
        echo "Please:\n";
        echo "1. Login to https://smsapi.ph/dashboard\n";
        echo "2. Generate a new API key\n";
        echo "3. Update SMS_API_PHILIPPINES_KEY in your .env file\n\n";
    }
}

echo "\n";
echo "NOTES:\n";
echo "- Make sure to check 'Send SMS Notifications' when creating announcements\n";
echo "- Farmers need valid Philippine phone numbers to receive SMS\n";
echo "- Check your SMS balance at: https://smsapi.ph/dashboard\n\n";
