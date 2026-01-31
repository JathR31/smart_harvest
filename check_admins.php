<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== All Admin Users ===\n";
$users = App\Models\User::whereIn('role', ['Admin', 'DA Admin', 'Superadmin'])
    ->orWhere('is_superadmin', true)
    ->get(['id', 'name', 'email', 'role', 'is_superadmin']);

foreach($users as $user) {
    echo sprintf(
        "ID: %d | Name: %s | Email: %s | Role: %s | is_superadmin: %s\n",
        $user->id,
        $user->name,
        $user->email,
        $user->role,
        $user->is_superadmin ? 'YES' : 'NO'
    );
}
echo "\nTotal: " . $users->count() . " admin users\n";
