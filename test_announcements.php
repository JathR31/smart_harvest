<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ANNOUNCEMENT SYSTEM TEST ===\n\n";

// Check total announcements
$total = \App\Models\Announcement::count();
echo "Total announcements in database: $total\n\n";

// Check active announcements (what farmers see)
$active = \App\Models\Announcement::active()->count();
echo "Active announcements (visible to farmers): $active\n\n";

// Show recent announcements
echo "Recent announcements:\n";
echo str_repeat('-', 80) . "\n";

$announcements = \App\Models\Announcement::latest()
    ->take(5)
    ->get(['id', 'title', 'content', 'priority', 'is_active', 'created_at']);

foreach ($announcements as $announcement) {
    echo "ID: {$announcement->id}\n";
    echo "Title: {$announcement->title}\n";
    echo "Content: " . substr($announcement->content, 0, 100) . "...\n";
    echo "Priority: {$announcement->priority}\n";
    echo "Active: " . ($announcement->is_active ? 'YES' : 'NO') . "\n";
    echo "Created: {$announcement->created_at}\n";
    echo str_repeat('-', 80) . "\n";
}

// Check farmers count
$farmers = \App\Models\User::where('role', 'Farmer')->count();
echo "\nTotal farmers in system: $farmers\n";

// Check farmers with phone numbers (for SMS)
$farmersWithPhone = \App\Models\User::where('role', 'Farmer')
    ->whereNotNull('phone_number')
    ->count();
echo "Farmers with phone numbers: $farmersWithPhone\n";

echo "\n✓ Test completed\n";
