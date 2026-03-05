<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = new \App\Services\PagasaWeatherService();
$reflection = new ReflectionClass($service);
$method = $reflection->getMethod('fetchPagasaPage');
$method->setAccessible(true);
$html = $method->invoke($service);

// Use DOM Parse HTML
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
$xpath = new DOMXPath($dom);
libxml_clear_errors();

echo "=== SYNOPSIS TEST ===\n";
$panelBodies = $xpath->query("//div[contains(@class, 'panel-body')]");
echo "Found " . $panelBodies->length . " panel-body elements\n\n";

$count = 0;
foreach ($panelBodies as $panel) {
    $text = trim($panel->textContent);
    if (stripos($text, 'SYNOPSIS') !== false || stripos($text, 'Shear Line') !== false) {
        $count++;
        echo "Panel #$count contains weather synopsis:\n";
        echo substr($text, 0, 500) . "\n";
        echo str_repeat("-", 80) . "\n\n";
        
        if ($count >= 3) break; // Show first 3
    }
}

echo "\n=== FARMING ADVISORIES TEST ===\n";
$listItems = $xpath->query("//li");
echo "Found " . $listItems->length . " list items total\n\n";

$count = 0;
foreach ($listItems as $item) {
    $text = trim($item->textContent);
    // Look for farming-related keywords
    if (stripos($text, 'farm') !== false || 
        stripos($text, 'crop') !== false || 
        stripos($text, 'rain') !== false || 
        stripos($text, 'drainage') !== false) {
        $count++;
        echo "Advisory #$count:\n";
        echo substr($text, 0, 200) . "...\n";
        echo str_repeat("-", 80) . "\n";
        
        if ($count >= 5) break; // Show first 5
    }
}

echo "\n=== TABLE DATA FOR CORDILLERA ===\n";
$tables = $xpath->query("//table");
echo "Found " . $tables->length . " tables\n\n";

foreach ($tables as $table) {
    $tableText = $table->textContent;
    if (stripos($tableText, 'Cordillera') !== false) {
        echo "Found table with Cordillera data:\n";
        
        // Extract rows
        $rows = $xpath->query(".//tr", $table);
        foreach ($rows as $row) {
            $rowText = trim($row->textContent);
            if (stripos($rowText, 'Cordillera') !== false) {
                echo "Row: " . substr($rowText, 0, 500) . "\n";
                echo str_repeat("-", 80) . "\n";
            }
        }
        break;
    }
}
