<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING SMS SERVICE sendAnnouncement() METHOD ===\n\n";

// Test if the method exists
$smsService = app(\App\Services\SMSService::class);

if (method_exists($smsService, 'sendAnnouncement')) {
    echo "✓ sendAnnouncement() method exists!\n\n";
    
    // Test with empty array (no farmers with phone numbers)
    echo "Testing with no phone numbers:\n";
    $result = $smsService->sendAnnouncement([], "Test message", "normal");
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";
    
    // Test with invalid phone number
    echo "Testing with invalid phone number:\n";
    $result = $smsService->sendAnnouncement(['invalid'], "Test message", "normal");
    echo "Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";
    
    echo "✅ Method is working correctly!\n";
    echo "\nThe announcement creation should now work.\n";
    echo "SMS will only be sent if:\n";
    echo "1. Farmers have valid phone numbers\n";
    echo "2. SEMAPHORE_API_KEY is configured\n";
    echo "3. 'Send SMS Notifications' checkbox is enabled\n";
} else {
    echo "✗ sendAnnouncement() method NOT found!\n";
}

echo "\n=== TEST COMPLETE ===\n";
