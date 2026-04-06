<?php
require_once 'ml_dataset_loader.php';

$loader = new DatasetLoader();

try {
    echo "Loading dataset...\n";
    list($df, $info) = $loader->load_dataset(dataset_id: 1);
    
    echo "Dataset: " . $info['name'] . "\n";
    echo "Records: " . count($df) . "\n\n";
    
    if (count($df) > 0) {
        echo "Columns: " . implode(", ", array_keys($df[0])) . "\n\n";
        
        echo "First row:\n";
        foreach ($df[0] as $key => $value) {
            echo "  $key: $value\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
