<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== All Users with their Roles ===\n\n";

$users = App\Models\User::orderBy('created_at', 'desc')->get();

foreach ($users as $user) {
    echo "Email: " . $user->email . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Role: '" . $user->role . "'\n";
    echo "Is Superadmin: " . ($user->is_superadmin ? 'YES' : 'NO') . "\n";
    echo "Email Verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
    echo "---\n";
}
