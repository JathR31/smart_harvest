<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = 'storage/app/public/datasets/1763374977_completeDataset__1_.xlsx';

if (!file_exists($file)) {
    die("File not found: $file\n");
}

$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load($file);
$sheet = $spreadsheet->getActiveSheet();

// Get headers (first row)
$headers = [];
foreach ($sheet->getRowIterator(1, 1) as $row) {
    foreach ($row->getCellIterator() as $cell) {
        $headers[] = $cell->getValue();
    }
}

echo "Columns in dataset:\n";
echo implode("\n", $headers);
echo "\n\nTotal columns: " . count($headers) . "\n";

// Get first data row
echo "\nFirst data row:\n";
$rowNum = 0;
foreach ($sheet->getRowIterator() as $row) {
    if ($rowNum == 0) { $rowNum++; continue; } // Skip header
    if ($rowNum == 1) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $i = 0;
        foreach ($cellIterator as $cell) {
            if ($i < count($headers)) {
                echo $headers[$i] . ": " . $cell->getValue() . "\n";
            }
            $i++;
            if ($i >= count($headers)) break;
        }
    }
    $rowNum++;
    if ($rowNum > 1) break;
}
