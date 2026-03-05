<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Available Users to Test Weather Features ===\n\n";

$users = App\Models\User::orderBy('role')->get();

foreach ($users as $user) {
    echo "Email: {$user->email}\n";
    echo "Phone: {$user->phone_number}\n";
    echo "Role: {$user->role}\n";
    echo "Municipality: {$user->municipality}\n";
    echo "Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
    echo "---\n";
}

echo "\nTotal Users: " . $users->count() . "\n";
