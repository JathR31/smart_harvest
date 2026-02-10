<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

echo "Checking municipalities in database...\n\n";

$municipalities = \Illuminate\Support\Facades\DB::table('crop_data')
    ->select('MUNICIPALITY')
    ->distinct()
    ->orderBy('MUNICIPALITY')
    ->limit(20)
    ->pluck('MUNICIPALITY');

echo "Found " . $municipalities->count() . " municipalities:\n";
foreach ($municipalities as $mun) {
    echo "  - $mun\n";
}

echo "\n\nChecking ML API format (normalized):\n";
foreach ($municipalities->take(5) as $mun) {
    $normalized = strtoupper(str_replace(' ', '', $mun));
    echo "  - $mun => $normalized\n";
}
