<?php

namespace App\Imports;

use App\Models\CropData;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Carbon\Carbon;

class CropDataImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    protected $recordsImported = 0;
    protected $userId;

    public function __construct($userId = null)
    {
        // Use provided user ID, or find first admin user, or first user
        $this->userId = $userId ?? User::where('is_superadmin', true)->first()?->id 
            ?? User::whereIn('role', ['Admin', 'DA Admin'])->first()?->id 
            ?? User::first()?->id 
            ?? 1;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->recordsImported++;

        // Map Excel columns to database fields
        // Laravel Excel converts headers to lowercase slugs
        // Headers: MUNICIPALITY, FARM TYPE, YEAR, MONTH, CROP, Area planted(ha), Area harvested(ha), Production(mt), Productivity(mt/ha)
        
        $municipality = $row['municipality'] ?? null;
        $cropType = $row['crop'] ?? null;
        $variety = $row['farm_type'] ?? $row['farm type'] ?? 'Standard';
        
        // Handle different possible column name formats for area planted
        $areaPlanted = $row['area_planted_ha'] ?? $row['area_plantedha'] ?? $row['area planted(ha)'] ?? $row['area_planted(ha)'] ?? 0;
        
        // Handle different possible column name formats for production
        $yieldAmount = $row['production_mt'] ?? $row['productionmt'] ?? $row['production(mt)'] ?? 0;
        
        // Build dates from YEAR and MONTH columns
        $year = $row['year'] ?? null;
        $month = $row['month'] ?? null;
        $plantingDate = null;
        $harvestDate = null;
        
        if ($year && $month) {
            try {
                // Convert month name to number (JAN -> 01, FEB -> 02, etc)
                $monthNum = Carbon::parse($month . ' 1, ' . $year)->format('m');
                $plantingDate = Carbon::create($year, $monthNum, 1);
                
                // Set harvest date to end of same month (since data is monthly aggregated)
                $harvestDate = $plantingDate->copy()->endOfMonth();
            } catch (\Exception $e) {
                // If date parsing fails, skip this row
                return null;
            }
        } else {
            // No date info, skip this row
            return null;
        }
        
        $temperature = $row['temperature'] ?? null;
        $rainfall = $row['rainfall'] ?? null;
        $humidity = $row['humidity'] ?? null;

        // Skip if required fields are missing
        if (!$municipality || !$cropType) {
            return null;
        }

        return new CropData([
            'user_id' => $this->userId,
            'municipality' => $municipality,
            'crop_type' => $cropType,
            'variety' => $variety,
            'area_planted' => (float) $areaPlanted,
            'yield_amount' => (float) $yieldAmount,
            'planting_date' => $plantingDate,
            'harvest_date' => $harvestDate,
            'status' => $harvestDate ? 'Harvested' : 'Planted',
            'temperature' => $temperature ? (float) $temperature : null,
            'rainfall' => $rainfall ? (float) $rainfall : null,
            'humidity' => $humidity ? (float) $humidity : null,
            'validation_status' => 'Validated',
        ]);
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 5000; // Process 5000 rows at a time for faster import
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 2000; // Insert 2000 rows per query for better performance
    }

    /**
     * @return int
     */
    public function getRecordsImported(): int
    {
        return $this->recordsImported;
    }
}
