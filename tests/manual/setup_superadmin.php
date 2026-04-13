<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== Setting up Superadmin: superadmin@smartharvest.ph ===\n\n";

// First, remove superadmin status from old superadmin and clear username
DB::table('users')
    ->where('email', '!=', 'superadmin@smartharvest.ph')
    ->where('admin_type', 'superadmin')
    ->update([
        'is_superadmin' => false,
        'admin_type' => null,
    ]);
echo "Cleared old superadmin username.\n";

// Check if user exists
$user = User::where('email', 'superadmin@smartharvest.ph')->first();

if ($user) {
    echo "User found: ID=" . $user->id . "\n";
    echo "Updating to superadmin...\n";
    
    // Update user to be superadmin
    DB::table('users')
        ->where('id', $user->id)
        ->update([
            'username' => 'superadmin@smartharvest.ph',
            'role' => 'Admin',
            'is_superadmin' => true,
            'admin_type' => 'superadmin',
            'password' => Hash::make('SuperAdminAccess123'),
        ]);
    
    echo "✓ User updated to superadmin!\n";
} else {
    echo "User not found, creating new superadmin...\n";
    
    // Create new superadmin with this email
    DB::table('users')->insert([
        'name' => 'Super Administrator',
        'email' => 'superadmin@smartharvest.ph',
        'username' => 'superadmin@smartharvest.ph',
        'password' => Hash::make('SuperAdminAccess123'),
        'role' => 'Admin',
        'is_superadmin' => true,
        'admin_type' => 'superadmin',
        'status' => 'Active',
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✓ Superadmin created!\n";
}

// Verify
$user = User::where('email', 'superadmin@smartharvest.ph')->first();
echo "\n=== Superadmin Details ===\n";
echo "Email: " . $user->email . "\n";
echo "Username: " . $user->username . "\n";
echo "Role: " . $user->role . "\n";
echo "is_superadmin: " . ($user->is_superadmin ? 'true' : 'false') . "\n";

// Verify password
if (Hash::check('SuperAdminAccess123', $user->password)) {
    echo "✓ Password 'SuperAdminAccess123' verified!\n";
} else {
    echo "✗ Password verification failed\n";
}

echo "\n=== Login Credentials ===\n";
echo "Username: superadmin@smartharvest.ph\n";
echo "Password: SuperAdminAccess123\n";
echo "\n=== Done ===\n";
