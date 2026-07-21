<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

$app = $app ?? \Illuminate\Foundation\Application::getInstance();
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "Starting RSBSA normalization script...\n";

$updated = 0;
$skipped = 0;

User::chunkById(100, function ($users) use (&$updated, &$skipped) {
    foreach ($users as $user) {
        $original = $user->rsbsa_number ?? '';
        $normalized = $original ? User::normalizeRsbsaNumber($original) : '';

        if (!$original && !$normalized) {
            $skipped++;
            continue;
        }

        if ($normalized !== $original) {
            $user->rsbsa_number = $normalized ?: null;
            $user->save();
            $updated++;
        }
    }
});

echo "Done. Updated: {$updated}, Skipped: {$skipped}\n";
