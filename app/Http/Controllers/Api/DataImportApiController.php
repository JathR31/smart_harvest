<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CropData;
use App\Models\ClimatePattern;
use App\Models\MarketPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DataImportApiController extends Controller
{
    /**
     * Get available datasets for import
     */
    public function getAvailableDatasets()
    {
        try {
            $datasets = [
                [
                    'id' => 'crop_production',
                    'name' => 'Crop Production Statistics (Monthly)',
                    'description' => 'Monthly crop production data including area planted/harvested, production volume, and productivity metrics',
                    'table' => 'crop_data',
                    'required_fields' => ['Municipality', 'Farm_Type', 'Year', 'Month', 'Crop', 'Area_Planted_Ha', 'Area_Harvested_Ha', 'Production_MT', 'Productivity_MT_Ha']
                ],
                [
                    'id' => 'climate_patterns',
                    'name' => 'Climate & Weather Data',
                    'description' => 'Historical climate patterns including rainfall, temperature, and humidity data',
                    'table' => 'climate_patterns',
                    'required_fields' => ['Year', 'Month', 'Municipality', 'Rainfall', 'Avg_Temperature', 'Humidity']
                ],
                [
                    'id' => 'market_prices',
                    'name' => 'Agricultural Market Prices',
                    'description' => 'Current and historical market prices for agricultural commodities',
                    'table' => 'market_prices',
                    'required_fields' => ['Date', 'Crop_Name', 'Price_Per_Kg', 'Market_Location', 'Demand_Level']
                ],
                [
                    'id' => 'livestock_poultry',
                    'name' => 'Livestock & Poultry Inventory (Bi-annual)',
                    'description' => 'Headcount of livestock and poultry from backyard and commercial farms',
                    'table' => 'livestock_inventory',
                    'required_fields' => ['Year', 'Period', 'Municipality', 'Animal_Type', 'Headcount', 'Farm_Type']
                ]
            ];

            return response()->json([
                'datasets' => $datasets,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Get Datasets Error: ' . $e->getMessage());
            return response()->json([
                'datasets' => [],
                'success' => false,
                'error' => 'Failed to load available datasets'
            ], 500);
        }
    }

    /**
     * Generate and download CSV template for a specific dataset
     */
    public function downloadTemplate(Request $request, $datasetId)
    {
        try {
            Log::info("Downloading template for dataset: $datasetId");

            $templates = [
                'crop_production' => [
                    'filename' => 'crop_production_template.csv',
                    'headers' => ['Municipality', 'Farm_Type', 'Year', 'Month', 'Crop', 'Area_Planted_Ha', 'Area_Harvested_Ha', 'Production_MT', 'Productivity_MT_Ha'],
                    'sample_data' => [
                        ['ATOK', 'IRRIGATED', '2015', 'JAN', 'CABBAGE', '98', '120', '2400', '20'],
                        ['ATOK', 'IRRIGATED', '2015', 'FEB', 'CABBAGE', '115', '76', '1216', '16']
                    ]
                ],
                'climate_patterns' => [
                    'filename' => 'climate_data_template.csv',
                    'headers' => ['Year', 'Month', 'Municipality', 'Rainfall', 'Avg_Temperature', 'Humidity'],
                    'sample_data' => [
                        ['2025', '1', 'La Trinidad', '120.5', '18.5', '75'],
                        ['2025', '1', 'Bokod', '150.2', '17.8', '80']
                    ]
                ],
                'market_prices' => [
                    'filename' => 'market_prices_template.csv',
                    'headers' => ['Date', 'Crop_Name', 'Price_Per_Kg', 'Market_Location', 'Demand_Level'],
                    'sample_data' => [
                        ['2025-02-05', 'Cabbage', '25.00', 'Baguio Public Market', 'high'],
                        ['2025-02-05', 'Lettuce', '35.00', 'La Trinidad Trading Post', 'moderate']
                    ]
                ],
                'livestock_poultry' => [
                    'filename' => 'livestock_inventory_template.csv',
                    'headers' => ['Year', 'Period', 'Municipality', 'Animal_Type', 'Headcount', 'Farm_Type'],
                    'sample_data' => [
                        ['2025', '1st Semester', 'La Trinidad', 'Chicken', '8240', 'Backyard'],
                        ['2025', '1st Semester', 'Bokod', 'Cattle', '156', 'Commercial']
                    ]
                ]
            ];

            if (!isset($templates[$datasetId])) {
                return response()->json(['error' => 'Template not found'], 404);
            }

            $template = $templates[$datasetId];

            // Create CSV content
            $output = fopen('php://temp', 'r+');
            
            // Add headers
            fputcsv($output, $template['headers']);
            
            // Add sample data
            foreach ($template['sample_data'] as $row) {
                fputcsv($output, $row);
            }
            
            rewind($output);
            $csv = stream_get_contents($output);
            fclose($output);

            return response($csv, 200)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $template['filename'] . '"');

        } catch (\Exception $e) {
            Log::error('Template Download Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate template'], 500);
        }
    }

    /**
     * Validate uploaded CSV file
     */
    public function validateFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:csv,txt|max:10240',
                'dataset_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $datasetId = $request->input('dataset_id');

            // Read and validate CSV
            $handle = fopen($file->getRealPath(), 'r');
            $headers = fgetcsv($handle);
            
            $errors = [];
            $rowCount = 0;
            $validRows = 0;

            // Define required fields for each dataset
            $requiredFields = $this->getRequiredFields($datasetId);

            // Validate headers
            $missingFields = array_diff($requiredFields, $headers);
            if (!empty($missingFields)) {
                $errors[] = "Missing required fields: " . implode(', ', $missingFields);
            }

            // Validate data rows
            while (($row = fgetcsv($handle)) !== false) {
                $rowCount++;
                
                // Check if row has correct number of columns
                if (count($row) !== count($headers)) {
                    $errors[] = "Row $rowCount: Column count mismatch";
                    continue;
                }

                // Basic validation for specific fields
                $rowData = array_combine($headers, $row);
                
                // Validate year if present
                if (isset($rowData['Year']) && !is_numeric($rowData['Year'])) {
                    $errors[] = "Row $rowCount: Invalid year format";
                }

                $validRows++;
            }

            fclose($handle);

            $isValid = empty($errors);

            return response()->json([
                'success' => $isValid,
                'valid' => $isValid,
                'row_count' => $rowCount,
                'valid_rows' => $validRows,
                'errors' => $errors,
                'message' => $isValid 
                    ? "$validRows records are ready to be imported."
                    : "Validation failed with " . count($errors) . " error(s)."
            ]);

        } catch (\Exception $e) {
            Log::error('File Validation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => ['Failed to validate file: ' . $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Import validated data into database
     */
    public function importData(Request $request)
    {
        try {
            Log::info('Data import request received');

            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
                'dataset_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $datasetId = $request->input('dataset_id');

            // Process CSV file
            $handle = fopen($file->getRealPath(), 'r');
            $headers = fgetcsv($handle);
            
            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) !== count($headers)) {
                        continue;
                    }

                    $data = array_combine($headers, $row);
                    
                    // Import based on dataset type
                    if ($datasetId === 'crop_production') {
                        $this->importCropData($data);
                    } elseif ($datasetId === 'climate_patterns') {
                        $this->importClimateData($data);
                    } elseif ($datasetId === 'market_prices') {
                        $this->importMarketPrice($data);
                    }

                    $imported++;
                }

                DB::commit();
                fclose($handle);

                Log::info("Successfully imported $imported records");

                return response()->json([
                    'success' => true,
                    'message' => "$imported records have been successfully imported to " . $this->getDatasetName($datasetId),
                    'imported_count' => $imported
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                fclose($handle);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Data Import Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent uploads history
     */
    public function getRecentUploads()
    {
        try {
            // Get recent data imports from various tables
            $recentCropImports = CropData::select(
                DB::raw("'Crop Production Data' as name"),
                DB::raw("'Crop production statistics' as description"),
                DB::raw('COUNT(*) as records'),
                DB::raw("'System' as uploaded_by"),
                DB::raw('MAX(created_at) as date')
            )->groupBy('name', 'description', 'uploaded_by')
            ->first();

            $recentClimateImports = ClimatePattern::select(
                DB::raw("'Climate Patterns' as name"),
                DB::raw("'Weather and climate data' as description"),
                DB::raw('COUNT(*) as records'),
                DB::raw("'System' as uploaded_by"),
                DB::raw('MAX(created_at) as date')
            )->groupBy('name', 'description', 'uploaded_by')
            ->first();

            $uploads = [];

            if ($recentCropImports) {
                $uploads[] = [
                    'id' => 'crop_' . time(),
                    'name' => $recentCropImports->name,
                    'description' => $recentCropImports->description,
                    'records' => $recentCropImports->records,
                    'uploaded_by' => $recentCropImports->uploaded_by,
                    'date' => Carbon::parse($recentCropImports->date)->diffForHumans(),
                    'status' => 'success'
                ];
            }

            if ($recentClimateImports) {
                $uploads[] = [
                    'id' => 'climate_' . time(),
                    'name' => $recentClimateImports->name,
                    'description' => $recentClimateImports->description,
                    'records' => $recentClimateImports->records,
                    'uploaded_by' => $recentClimateImports->uploaded_by,
                    'date' => Carbon::parse($recentClimateImports->date)->diffForHumans(),
                    'status' => 'success'
                ];
            }

            return response()->json([
                'uploads' => $uploads,
                'success' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Recent Uploads Error: ' . $e->getMessage());
            return response()->json([
                'uploads' => [],
                'success' => false
            ], 500);
        }
    }

    // Helper methods

    private function getRequiredFields($datasetId)
    {
        $fields = [
            'crop_production' => ['Municipality', 'Farm_Type', 'Year', 'Month', 'Crop', 'Area_Planted_Ha', 'Area_Harvested_Ha', 'Production_MT', 'Productivity_MT_Ha'],
            'climate_patterns' => ['Year', 'Month', 'Municipality', 'Rainfall', 'Avg_Temperature', 'Humidity'],
            'market_prices' => ['Date', 'Crop_Name', 'Price_Per_Kg', 'Market_Location', 'Demand_Level'],
            'livestock_poultry' => ['Year', 'Period', 'Municipality', 'Animal_Type', 'Headcount', 'Farm_Type']
        ];

        return $fields[$datasetId] ?? [];
    }

    private function getDatasetName($datasetId)
    {
        $names = [
            'crop_production' => 'Crop Production Statistics (Monthly)',
            'climate_patterns' => 'Climate & Weather Data',
            'market_prices' => 'Agricultural Market Prices',
            'livestock_poultry' => 'Livestock & Poultry Inventory'
        ];

        return $names[$datasetId] ?? 'Unknown Dataset';
    }

    private function importCropData($data)
    {
        CropData::create([
            'crop_type' => $data['Crop'] ?? null,
            'variety' => $data['Farm_Type'] ?? null,
            'municipality' => $data['Municipality'] ?? null,
            'planting_date' => isset($data['Year'], $data['Month']) 
                ? $this->getDateFromMonth($data['Year'], $data['Month']) 
                : null,
            'yield_amount' => isset($data['Production_MT']) ? floatval($data['Production_MT']) * 1000 : 0, // Convert MT to kg
            'area_planted' => $data['Area_Planted_Ha'] ?? 0,
        ]);
    }

    private function importClimateData($data)
    {
        ClimatePattern::create([
            'year' => $data['Year'] ?? null,
            'month' => $data['Month'] ?? null,
            'municipality' => $data['Municipality'] ?? null,
            'rainfall' => $data['Rainfall'] ?? 0,
            'avg_temperature' => $data['Avg_Temperature'] ?? 0,
            'humidity' => $data['Humidity'] ?? 0,
        ]);
    }

    private function importMarketPrice($data)
    {
        MarketPrice::create([
            'crop_name' => $data['Crop_Name'] ?? null,
            'price_per_kg' => $data['Price_Per_Kg'] ?? 0,
            'market_location' => $data['Market_Location'] ?? null,
            'demand_level' => $data['Demand_Level'] ?? 'moderate',
            'is_active' => true,
        ]);
    }

    private function getDateFromQuarter($year, $quarter)
    {
        $quarterMap = [
            'Q1' => '01-15',
            'Q2' => '04-15',
            'Q3' => '07-15',
            'Q4' => '10-15',
        ];

        $date = $quarterMap[$quarter] ?? '01-01';
        return "$year-$date";
    }

    private function getDateFromMonth($year, $month)
    {
        $monthMap = [
            'JAN' => '01', 'JANUARY' => '01',
            'FEB' => '02', 'FEBRUARY' => '02',
            'MAR' => '03', 'MARCH' => '03',
            'APR' => '04', 'APRIL' => '04',
            'MAY' => '05',
            'JUN' => '06', 'JUNE' => '06',
            'JUL' => '07', 'JULY' => '07',
            'AUG' => '08', 'AUGUST' => '08',
            'SEP' => '09', 'SEPTEMBER' => '09',
            'OCT' => '10', 'OCTOBER' => '10',
            'NOV' => '11', 'NOVEMBER' => '11',
            'DEC' => '12', 'DECEMBER' => '12',
        ];

        $monthNum = $monthMap[strtoupper($month)] ?? (is_numeric($month) ? str_pad($month, 2, '0', STR_PAD_LEFT) : '01');
        return "$year-$monthNum-01";
    }
}
