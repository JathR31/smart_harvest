<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║              SMS API PHILIPPINES - ACTUAL SEND TEST                       ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

// Check recent announcements
echo "1. Checking Recent Announcements...\n";
echo str_repeat('-', 79) . "\n";

$announcements = \App\Models\Announcement::latest()->take(5)->get();

if ($announcements->count() > 0) {
    echo "Found {$announcements->count()} recent announcement(s):\n\n";
    
    foreach ($announcements as $announcement) {
        echo "  ID: {$announcement->id}\n";
        echo "  Title: {$announcement->title}\n";
        echo "  Content: " . substr($announcement->content, 0, 50) . "...\n";
        echo "  SMS Sent: " . ($announcement->sms_sent ? 'Yes' : 'No') . "\n";
        echo "  Created: {$announcement->created_at}\n";
        echo "  " . str_repeat('-', 75) . "\n";
    }
} else {
    echo "No announcements found in database.\n";
}
echo "\n";

// Check users with phone numbers
echo "2. Checking Users with Phone Numbers...\n";
echo str_repeat('-', 79) . "\n";

$usersWithPhone = \App\Models\User::where('role', 'farmer')
    ->where(function($query) {
        $query->whereNotNull('phone')
              ->orWhereNotNull('phone_number');
    })
    ->take(10)
    ->get();

echo "Found {$usersWithPhone->count()} farmer(s) with phone numbers:\n\n";

if ($usersWithPhone->count() > 0) {
    foreach ($usersWithPhone as $user) {
        $phone = $user->phone ?? $user->phone_number;
        echo "  User: {$user->name}\n";
        echo "  Phone: {$phone}\n";
        echo "  Verified: " . ($user->phone_verified_at ? 'Yes' : 'No') . "\n";
        echo "  " . str_repeat('-', 75) . "\n";
    }
} else {
    echo "  No farmers with phone numbers found.\n";
}
echo "\n";

// Test actual SMS send
echo "3. Testing Actual SMS Send...\n";
echo str_repeat('-', 79) . "\n";

$apiKey = env('SMS_API_PHILIPPINES_KEY');
$simulationMode = env('SMS_SIMULATION_MODE', false);

echo "Configuration:\n";
echo "  API Key: " . ($apiKey ? 'Configured ✓' : 'Missing ✗') . "\n";
echo "  Simulation Mode: " . ($simulationMode ? 'Enabled' : 'Disabled') . "\n\n";

// Prompt for test phone number
echo "Enter a test phone number to send SMS (or press Enter to skip): ";
$testPhone = trim(fgets(STDIN));

if (!empty($testPhone)) {
    echo "\nSending test SMS to: {$testPhone}\n";
    echo str_repeat('-', 79) . "\n";
    
    try {
        $smsService = app(\App\Services\SMSApiPhilippinesService::class);
        
        // Validate phone number
        if (!$smsService->isValidPhoneNumber($testPhone)) {
            echo "✗ Invalid phone number format. Use formats like:\n";
            echo "  - 09171234567\n";
            echo "  - +639171234567\n";
            echo "  - 639171234567\n";
            echo "  - 9171234567\n\n";
        } else {
            $message = "TEST: This is a test message from SmartHarvest SMS API Philippines integration. Time: " . date('Y-m-d H:i:s');
            
            echo "Sending message: {$message}\n\n";
            
            $result = $smsService->sendMessage($testPhone, $message, 'SmartHarvest');
            
            echo "Result:\n";
            echo "  Success: " . ($result['success'] ? 'Yes ✓' : 'No ✗') . "\n";
            echo "  Message: {$result['message']}\n";
            
            if (isset($result['response'])) {
                echo "  API Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n";
            }
            
            if (isset($result['error'])) {
                echo "  Error Details: {$result['error']}\n";
            }
            echo "\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    }
} else {
    echo "Skipping actual SMS send test.\n\n";
}

// Check API connection
echo "4. Testing API Connection...\n";
echo str_repeat('-', 79) . "\n";

try {
    $smsService = app(\App\Services\SMSApiPhilippinesService::class);
    
    echo "Attempting to check balance...\n";
    $balance = $smsService->checkBalance();
    
    if ($balance['success']) {
        echo "✓ API connection successful!\n";
        if (isset($balance['balance'])) {
            echo "  Current Balance: ₱" . number_format($balance['balance'], 2) . "\n";
        }
        if (isset($balance['response'])) {
            echo "  Full Response: " . json_encode($balance['response'], JSON_PRETTY_PRINT) . "\n";
        }
    } else {
        echo "✗ API connection issue:\n";
        echo "  Message: {$balance['message']}\n";
        
        if (isset($balance['error'])) {
            echo "  Error: {$balance['error']}\n";
        }
        
        if (isset($balance['status_code'])) {
            echo "  HTTP Status: {$balance['status_code']}\n";
            
            if ($balance['status_code'] == 401) {
                echo "\n⚠ AUTHENTICATION ERROR:\n";
                echo "  Your API key might be invalid or expired.\n";
                echo "  Please check your API key at: https://smsapi.ph/dashboard\n";
            } elseif ($balance['status_code'] == 403) {
                echo "\n⚠ FORBIDDEN:\n";
                echo "  Your account might not have permission or might be inactive.\n";
            } elseif ($balance['status_code'] >= 500) {
                echo "\n⚠ SERVER ERROR:\n";
                echo "  The SMS API Philippines service might be temporarily down.\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// Summary
echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                           DIAGNOSTIC SUMMARY                              ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

if (!$apiKey) {
    echo "❌ ISSUE: SMS API key not configured\n";
    echo "   Solution: Add SMS_API_PHILIPPINES_KEY to your .env file\n\n";
}

if ($usersWithPhone->count() == 0) {
    echo "⚠ WARNING: No farmers with phone numbers\n";
    echo "   Announcements won't be sent if there are no recipients\n\n";
}

if ($announcements->count() > 0) {
    $smsAnnouncements = $announcements->where('sms_sent', true);
    if ($smsAnnouncements->count() == 0) {
        echo "⚠ NOTICE: No announcements have SMS enabled\n";
        echo "   Make sure to check 'Send SMS Notifications' when creating announcements\n\n";
    }
}

echo "TROUBLESHOOTING STEPS:\n";
echo "1. Verify your API key at: https://smsapi.ph/dashboard\n";
echo "2. Check if your account has sufficient balance\n";
echo "3. Ensure your account is active and verified\n";
echo "4. Test sending a message using the SMS API Philippines dashboard\n";
echo "5. Check if the phone number format is correct (Philippine numbers)\n\n";

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                           TEST COMPLETE                                   ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n";
