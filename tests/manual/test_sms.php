<?php

/**
 * Test script for SMS/OTP functionality
 * 
 * This script tests the Semaphore SMS service integration
 * Run: php test_sms.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Services\SMSService;
use Illuminate\Support\Facades\Log;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=====================================\n";
echo "SMS/OTP Service Test\n";
echo "=====================================\n\n";

// Check if API key is configured
$apiKey = env('SEMAPHORE_API_KEY');

if (!$apiKey) {
    echo "❌ ERROR: SEMAPHORE_API_KEY not configured in .env file\n\n";
    echo "To fix this:\n";
    echo "1. Sign up at https://semaphore.co/\n";
    echo "2. Get your API key from the dashboard\n";
    echo "3. Add to .env file: SEMAPHORE_API_KEY=your_api_key_here\n\n";
    exit(1);
}

echo "✓ API Key configured: " . substr($apiKey, 0, 10) . "...\n\n";

// Test phone number (replace with your actual test number)
echo "Enter test phone number (format: 09XXXXXXXXX): ";
$testPhone = trim(fgets(STDIN));

if (empty($testPhone)) {
    echo "❌ No phone number provided\n";
    exit(1);
}

// Generate test OTP
$testOTP = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

echo "\nGenerated OTP: {$testOTP}\n";
echo "Sending SMS...\n\n";

// Initialize SMS service
$smsService = new SMSService();

// Send OTP
$result = $smsService->sendOTP($testPhone, $testOTP);

echo "=====================================\n";
echo "RESULT:\n";
echo "=====================================\n";
echo "Success: " . ($result['success'] ? 'YES ✓' : 'NO ✗') . "\n";
echo "Message: {$result['message']}\n";

if (isset($result['message_id'])) {
    echo "Message ID: {$result['message_id']}\n";
}

echo "\n";

if ($result['success']) {
    echo "✓ SMS sent successfully!\n";
    echo "Check your phone for the OTP code: {$testOTP}\n\n";
    
    echo "Test Summary:\n";
    echo "- API Integration: ✓ Working\n";
    echo "- SMS Delivery: ✓ Sent\n";
    echo "- Phone Number: {$testPhone}\n";
} else {
    echo "❌ SMS failed to send\n";
    echo "Possible issues:\n";
    echo "- Invalid API key\n";
    echo "- Insufficient credits\n";
    echo "- Invalid phone number format\n";
    echo "- Network/API error\n\n";
    
    echo "Check logs in storage/logs/laravel.log for more details\n";
}

echo "\n";
echo "=====================================\n";
echo "Test Complete\n";
echo "=====================================\n";
