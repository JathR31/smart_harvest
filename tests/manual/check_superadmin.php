<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== Fixing Superadmin Password ===\n\n";

$user = User::where('username', 'smartharvestsuperadmin')->first();

if ($user) {
    echo "User found: ID=" . $user->id . "\n";
    
    // Reset password directly using DB to avoid double-hashing
    $hashedPassword = Hash::make('Admin123');
    DB::table('users')
        ->where('id', $user->id)
        ->update(['password' => $hashedPassword]);
    
    echo "Password reset to 'Admin123' using direct DB update.\n";
    
    // Verify
    $user->refresh();
    if (Hash::check('Admin123', $user->password)) {
        echo "✓ Password verification PASSED!\n";
    } else {
        echo "✗ Password verification FAILED!\n";
    }
} else {
    echo "User NOT found!\n";
}

echo "\n=== Done ===\n";
