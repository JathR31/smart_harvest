<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = new \App\Services\PagasaWeatherService();

// Use reflection to call private method
$reflection = new ReflectionClass($service);
$method = $reflection->getMethod('fetchPagasaPage');
$method->setAccessible(true);
$html = $method->invoke($service);

if ($html) {
    echo "=== HTML FETCHED SUCCESSFULLY ===\n";
    echo "Length: " . strlen($html) . " bytes\n\n";
    
    // Check for SYNOPSIS
    if (preg_match('/SYNOPSIS/i', $html)) {
        echo "✓ Found SYNOPSIS keyword\n";
        if (preg_match('/SYNOPSIS(.{0,500})/is', $html, $match)) {
            echo "Context around SYNOPSIS:\n";
            echo substr($match[0], 0, 300) . "...\n\n";
        }
    }
    
    // Check for FARMING ADVISORIES
    if (preg_match('/FARMING ADVISORIES/i', $html)) {
        echo "✓ Found FARMING ADVISORIES keyword\n";
        if (preg_match('/FARMING ADVISORIES(.{0,800})/is', $html, $match)) {
            echo "Context around FARMING ADVISORIES:\n";
            echo substr($match[0], 0, 500) . "...\n\n";
        }
    }
    
    // Check for Cordillera
    if (preg_match('/Cordillera/i', $html)) {
        echo "✓ Found Cordillera keyword\n";
        if (preg_match('/Cordillera(.{0,500})/is', $html, $match)) {
            echo "Context around Cordillera:\n";
            echo substr($match[0], 0, 300) . "...\n\n";
        }
    }
    
    // Check for Benguet municipalities
    $municipalities = ['La Trinidad', 'Baguio', 'Benguet', 'Tuba', 'Itogon'];
    foreach ($municipalities as $muni) {
        if (stripos($html, $muni) !== false) {
            echo "✓ Found municipality: $muni\n";
        }
    }
    
    // Save first 5000 chars to see structure
    file_put_contents('pagasa_sample.html', substr($html, 0, 10000));
    echo "\n✓ Saved first 10000 chars to pagasa_sample.html\n";
    
} else {
    echo "✗ Failed to fetch HTML\n";
}
