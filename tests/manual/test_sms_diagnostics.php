<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║           SMS API PHILIPPINES - DETAILED DIAGNOSTICS                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

// 1. Check Configuration
echo "1. Configuration Check\n";
echo str_repeat('-', 79) . "\n";

$apiKey = env('SMS_API_PHILIPPINES_KEY');
$simulationMode = env('SMS_SIMULATION_MODE', false);

echo "API Key: " . ($apiKey ? '✓ Configured (' . substr($apiKey, 0, 10) . '...)' : '✗ Missing') . "\n";
echo "Simulation Mode: " . ($simulationMode ? 'Enabled' : 'Disabled') . "\n";
echo "API Endpoint: https://api.smsapi.ph/v1\n\n";

// 2. Check Recent Announcements
echo "2. Recent Announcements\n";
echo str_repeat('-', 79) . "\n";

$announcements = \App\Models\Announcement::orderBy('created_at', 'desc')->take(3)->get();

if ($announcements->count() > 0) {
    echo "Found {$announcements->count()} recent announcement(s):\n\n";
    foreach ($announcements as $announcement) {
        echo "  ID: {$announcement->id}\n";
        echo "  Title: {$announcement->title}\n";
        echo "  SMS Sent: " . ($announcement->sms_sent ? '✓ Yes' : '✗ No') . "\n";
        echo "  Created: {$announcement->created_at}\n\n";
    }
} else {
    echo "No announcements found.\n\n";
}

// 3. Check Farmers with Phone Numbers
echo "3. Farmers with Phone Numbers\n";
echo str_repeat('-', 79) . "\n";

$farmers = \App\Models\User::where('role', 'farmer')
    ->where(function($query) {
        $query->whereNotNull('phone')
              ->orWhereNotNull('phone_number');
    })
    ->get();

echo "Total farmers with phone: {$farmers->count()}\n";

if ($farmers->count() > 0) {
    echo "\nFirst 5 farmers:\n";
    foreach ($farmers->take(5) as $farmer) {
        $phone = $farmer->phone ?? $farmer->phone_number;
        echo "  {$farmer->name}: {$phone}\n";
    }
    echo "\n";
} else {
    echo "⚠ WARNING: No farmers have phone numbers!\n";
    echo "  You need to add phone numbers for farmers to receive SMS.\n\n";
}

// 4. Test API Connection with Detailed Error Reporting
echo "4. API Connection Test\n";
echo str_repeat('-', 79) . "\n";

try {
    $smsService = app(\App\Services\SMSApiPhilippinesService::class);
    
    // Use reflection to call protected methods for testing
    $reflection = new ReflectionClass($smsService);
    $normalizeMethod = $reflection->getMethod('normalizePhoneNumber');
    $normalizeMethod->setAccessible(true);
    
    echo "Testing phone normalization:\n";
    $testNumber = '09171234567';
    $normalized = $normalizeMethod->invoke($smsService, $testNumber);
    echo "  Input: {$testNumber} → Output: {$normalized}\n\n";
    
    // Test actual API call
    echo "Testing API balance check...\n";
    
    $client = new \Illuminate\Support\Facades\Http;
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->get('https://api.smsapi.ph/v1/balance');
    
    echo "  Status Code: {$response->status()}\n";
    echo "  Response: " . $response->body() . "\n\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "✓ API Connection Successful!\n";
        if (isset($data['balance'])) {
            echo "  Balance: ₱" . number_format($data['balance'], 2) . "\n";
        }
    } else {
        echo "✗ API Connection Failed\n";
        
        if ($response->status() == 401) {
            echo "\n❌ AUTHENTICATION ERROR (401 Unauthorized)\n";
            echo "  Your API key is INVALID or EXPIRED.\n";
            echo "  Current key: {$apiKey}\n\n";
            echo "  SOLUTION:\n";
            echo "  1. Go to https://smsapi.ph/dashboard\n";
            echo "  2. Login to your account\n";
            echo "  3. Generate a new API key\n";
            echo "  4. Update SMS_API_PHILIPPINES_KEY in your .env file\n\n";
        } elseif ($response->status() == 403) {
            echo "\n❌ FORBIDDEN (403)\n";
            echo "  Your account may be inactive or suspended.\n";
            echo "  Contact: https://smsapi.ph/support\n\n";
        } elseif ($response->status() >= 500) {
            echo "\n❌ SERVER ERROR ({$response->status()})\n";
            echo "  SMS API Philippines service may be temporarily down.\n\n";
        }
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  " . $e->getFile() . ":" . $e->getLine() . "\n\n";
}

// 5. Test Sending SMS (if we have a farmer with phone)
if ($farmers->count() > 0 && $apiKey) {
    echo "5. Test SMS Send\n";
    echo str_repeat('-', 79) . "\n";
    
    $testFarmer = $farmers->first();
    $testPhone = $testFarmer->phone ?? $testFarmer->phone_number;
    
    echo "Attempting to send test SMS to: {$testFarmer->name} ({$testPhone})\n\n";
    
    try {
        $smsService = app(\App\Services\SMSApiPhilippinesService::class);
        
        $message = "TEST: SmartHarvest SMS test " . date('Y-m-d H:i:s');
        
        // Direct API call for detailed debugging
        echo "Sending via API...\n";
        
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.smsapi.ph/v1/sms/send', [
            'recipient' => $testPhone,
            'message' => $message,
            'sender_name' => 'SmartHarvest',
        ]);
        
        echo "  HTTP Status: {$response->status()}\n";
        echo "  Response Body: " . $response->body() . "\n\n";
        
        if ($response->successful()) {
            echo "✓ SMS SENT SUCCESSFULLY!\n";
            echo "  Check the phone: {$testPhone}\n\n";
        } else {
            echo "✗ SMS SEND FAILED\n";
            $errorData = $response->json();
            if (isset($errorData['message'])) {
                echo "  Error: {$errorData['message']}\n";
            }
            if (isset($errorData['errors'])) {
                echo "  Details: " . json_encode($errorData['errors'], JSON_PRETTY_PRINT) . "\n";
            }
            echo "\n";
        }
        
    } catch (\Exception $e) {
        echo "✗ Exception: " . $e->getMessage() . "\n\n";
    }
}

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                           SUMMARY                                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "KEY FINDINGS:\n";

if (!$apiKey) {
    echo "❌ No API key configured\n";
} else {
    echo "✓ API key is configured\n";
}

if ($farmers->count() == 0) {
    echo "⚠ No farmers with phone numbers in database\n";
} else {
    echo "✓ {$farmers->count()} farmer(s) with phone numbers\n";
}

if ($announcements->count() > 0) {
    $smsEnabled = $announcements->where('sms_sent', true)->count();
    if ($smsEnabled == 0) {
        echo "⚠ No announcements have SMS enabled (sms_sent checkbox not checked)\n";
    } else {
        echo "✓ {$smsEnabled} announcement(s) with SMS enabled\n";
    }
}

echo "\nNEXT STEPS:\n";
echo "1. If API key is invalid, get a new one from https://smsapi.ph/dashboard\n";
echo "2. Make sure to check 'Send SMS Notifications' when creating announcements\n";
echo "3. Ensure farmers have valid Philippine phone numbers\n";
echo "4. Check your SMS balance at https://smsapi.ph/dashboard\n\n";
