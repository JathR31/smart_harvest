<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CREATING TEST ANNOUNCEMENT ===\n\n";

// Get a DA Admin user
$daAdmin = \App\Models\User::where('role', 'DA Admin')
    ->orWhere('role', 'Admin')
    ->orWhere('is_superadmin', true)
    ->first();

if (!$daAdmin) {
    echo "❌ No DA Admin found. Creating test admin...\n";
    $daAdmin = \App\Models\User::create([
        'name' => 'Test DA Officer',
        'email' => 'da_test@test.com',
        'password' => bcrypt('password'),
        'role' => 'DA Admin',
        'municipality' => 'La Trinidad',
    ]);
    echo "✓ Created test DA Admin\n";
}

echo "Creating announcement by: {$daAdmin->name} ({$daAdmin->email})\n\n";

// Create a test announcement
$announcement = \App\Models\Announcement::create([
    'created_by' => $daAdmin->id,
    'title' => 'Weather Advisory: Heavy Rains Expected',
    'content' => 'Dear farmers, PAGASA forecasts heavy rainfall in the next 3 days. Please secure your crops and delay planting activities until weather improves. Stay safe!',
    'type' => 'weather',
    'priority' => 'high',
    'is_active' => true,
    'published_at' => now(),
]);

echo "✓ Announcement created successfully!\n\n";
echo "ID: {$announcement->id}\n";
echo "Title: {$announcement->title}\n";
echo "Priority: {$announcement->priority}\n";
echo "Type: {$announcement->type}\n";
echo "Active: " . ($announcement->is_active ? 'YES' : 'NO') . "\n";

// Create another one
$announcement2 = \App\Models\Announcement::create([
    'created_by' => $daAdmin->id,
    'title' => 'Market Update: Tomato Prices Rising',
    'content' => 'Good news for tomato farmers! Market prices for tomatoes have increased by 15% this week. Best time to harvest and sell your produce.',
    'type' => 'market',
    'priority' => 'normal',
    'is_active' => true,
    'published_at' => now(),
]);

echo "\n✓ Second announcement created!\n";
echo "ID: {$announcement2->id}\n";
echo "Title: {$announcement2->title}\n";

// Check if farmers can see them
$visibleToFarmers = \App\Models\Announcement::active()->count();
echo "\nAnnouncements visible to farmers: $visibleToFarmers\n";

echo "\n✅ Test announcements created successfully!\n";
echo "Farmers should now see these announcements in their dashboard.\n";
