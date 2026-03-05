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

echo "=== SEARCHING FOR FARMING ADVISORIES SECTION ===\n\n";

// Look for headings containing "FARMING ADVISORIES"
$headings = $xpath->query("//div[contains(@class, 'panel-heading')]");
foreach ($headings as $heading) {
    if (stripos($heading->textContent, 'FARMING') !== false) {
        echo "Found heading: " . trim($heading->textContent) . "\n\n";
        
        // Get the parent panel
        $parent = $heading->parentNode;
        
        // Find panel-body within this panel
        $bodies = $xpath->query(".//div[contains(@class, 'panel-body')]", $parent);
        foreach ($bodies as $body) {
            echo "Panel body content:\n";
            echo substr($body->textContent, 0, 1000) . "...\n\n";
            
            // Find list items in this body
            $listItems = $xpath->query(".//li", $body);
            echo "Found " . $listItems->length . " list items\n";
            foreach ($listItems as $item) {
                $text = trim($item->textContent);
                if (strlen($text) > 20) {
                    echo "- " . substr($text, 0, 150) . "\n";
                }
            }
            break 2;
        }
    }
}
