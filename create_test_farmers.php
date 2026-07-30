<?php
/**
 * Quick script to create test farmers with RSBSA numbers for login testing
 * Run: php create_test_farmers.php
 */

require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Test farmers with RSBSA numbers (Benguet format: 4-25-25-XXX-XXXXX)
$testFarmers = [
    [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan.delacruz@farmers.ph',
        'phone' => '+639171234567',
        'rsbsa_number' => '4-25-25-001-00001',
        'password' => 'Test@1234',
        'municipality' => 'LA TRINIDAD',
    ],
    [
        'name' => 'Maria Santos',
        'email' => 'maria.santos@farmers.ph',
        'phone' => '+639181234568',
        'rsbsa_number' => '4-25-25-001-00002',
        'password' => 'Test@1234',
        'municipality' => 'ATOK',
    ],
    [
        'name' => 'Pedro Reyes',
        'email' => 'pedro.reyes@farmers.ph',
        'phone' => '+639191234569',
        'rsbsa_number' => '4-25-25-001-00003',
        'password' => 'Test@1234',
        'municipality' => 'BAGUIO CITY',
    ],
    [
        'name' => 'Rosa Gonzales',
        'email' => 'rosa.gonzales@farmers.ph',
        'phone' => '+639201234570',
        'rsbsa_number' => '4-25-25-002-00001',
        'password' => 'Test@1234',
        'municipality' => 'ITOGON',
    ],
    [
        'name' => 'Carlos Fernandez',
        'email' => 'carlos.fernandez@farmers.ph',
        'phone' => '+639211234571',
        'rsbsa_number' => '4-25-25-002-00002',
        'password' => 'Test@1234',
        'municipality' => 'KIBUNGAN',
    ],
];

echo "Creating test farmers with RSBSA numbers...\n";
echo str_repeat("=", 80) . "\n";

foreach ($testFarmers as $farmer) {
    // Check if farmer already exists
    $existing = User::where('email', $farmer['email'])
        ->orWhere('rsbsa_number', $farmer['rsbsa_number'])
        ->first();
    
    if ($existing) {
        echo "⏭️  SKIPPED: {$farmer['name']} ({$farmer['rsbsa_number']}) - Already exists\n";
        continue;
    }
    
    try {
        User::create([
            'name' => $farmer['name'],
            'email' => $farmer['email'],
            'phone' => $farmer['phone'],
            'rsbsa_number' => $farmer['rsbsa_number'],
            'password' => Hash::make($farmer['password']),
            'role' => 'Farmer',
            'municipality' => $farmer['municipality'],
            'email_verified_at' => now(),
            'password_set_at' => now(),
        ]);
        
        echo "✅ CREATED: {$farmer['name']}\n";
        echo "   RSBSA: {$farmer['rsbsa_number']}\n";
        echo "   Email: {$farmer['email']}\n";
        echo "   Password: {$farmer['password']}\n";
        echo "\n";
    } catch (\Exception $e) {
        echo "❌ FAILED: {$farmer['name']} - {$e->getMessage()}\n";
    }
}

echo str_repeat("=", 80) . "\n";
echo "✅ Test farmer creation completed!\n";
echo "\n";
echo "📋 TEST CREDENTIALS SUMMARY:\n";
echo str_repeat("-", 80) . "\n";
echo "Login using RSBSA (no password required):\n";
foreach ($testFarmers as $farmer) {
    echo "  • RSBSA: {$farmer['rsbsa_number']}\n";
}
echo "\n";
echo "Login using Email + Password:\n";
foreach ($testFarmers as $farmer) {
    echo "  • Email: {$farmer['email']} | Password: {$farmer['password']}\n";
}
