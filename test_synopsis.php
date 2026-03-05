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

echo "=== TESTING SYNOPSIS EXTRACTION ===\n\n";

// Look for panels with SYNOPSIS
$panelBodies = $xpath->query("//div[contains(@class, 'panel-body')]");
echo "Total panel-body elements: " . $panelBodies->length . "\n\n";

foreach ($panelBodies as $panel) {
    $text = $panel->textContent;
    if (stripos($text, 'SYNOPSIS') !== false) {
        echo "Found panel containing SYNOPSIS keyword\n";
        echo "Full text:\n" . substr($text, 0, 500) . "\n\n";
       
        $parts = preg_split('/SYNOPSIS/i', $text, 2);
        if (isset($parts[1])) {
            echo "Text after SYNOPSIS split:\n";
            echo substr($parts[1], 0, 500) . "\n\n";
        }
        break;
    }
}

// Also check for panel-heading
$panelHeadings = $xpath->query("//div[contains(@class, 'panel-heading')]");
foreach ($panelHeadings as $heading) {
    if (stripos($heading->textContent, 'SYNOPSIS') !== false) {
        echo "Found panel-heading with SYNOPSIS\n";
        echo "Heading text: " . $heading->textContent . "\n";
        
        $parent = $heading->parentNode;
        $bodies = $xpath->query(".//div[contains(@class, 'panel-body')]", $parent);
        foreach ($bodies as $body) {
            echo "Panel body after heading:\n";
            echo substr($body->textContent, 0, 500) . "\n";
        }
        break;
    }
}
