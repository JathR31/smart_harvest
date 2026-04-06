<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = new \App\Services\PagasaWeatherService();
$reflection = new ReflectionClass($service);
$method = $reflection->getMethod('fetchPagasaPage');
$method->setAccessible(true);
$html = $method->invoke($service);

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
$xpath = new DOMXPath($dom);
libxml_clear_errors();

echo "=== ANALYZING WEATHER TABLE STRUCTURE ===\n\n";

$tables = $xpath->query("//table");
foreach ($tables as $table) {
    $tableText = $table->textContent;
    if (stripos($tableText, 'Cordillera') !== false) {
        echo "Found weather forecast table\n\n";
        
        // Get header row
        $headerRows = $xpath->query(".//tr", $table);
        $rowNum = 0;
        foreach ($headerRows as $row) {
            $rowNum++;
            $cells = $xpath->query(".//td | .//th", $row);
            echo "Row $rowNum has " . $cells->length . " cells:\n";
            
            $cellNum = 0;
            foreach ($cells as $cell) {
                $cellNum++;
                $text = trim(substr($cell->textContent, 0, 100));
                echo "  Cell $cellNum: $text\n";
            }
            echo "\n";
            
            if ($rowNum >= 3) break; // Show first 3 rows
        }
        break;
    }
}
