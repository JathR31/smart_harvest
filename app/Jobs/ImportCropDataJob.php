<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CropDataImport;
use App\Models\AdminActivityLog;

class ImportCropDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes
    public $tries = 3; // Retry 3 times on failure

    protected $filePath;
    protected $datasetName;
    protected $originalFileName;
    protected $adminEmail;
    protected $ipAddress;
    protected $userAgent;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $datasetName, $originalFileName, $adminEmail, $ipAddress, $userAgent)
    {
        $this->filePath = $filePath;
        $this->datasetName = $datasetName;
        $this->originalFileName = $originalFileName;
        $this->adminEmail = $adminEmail;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);

        // Disable query logging for faster imports
        \DB::connection()->disableQueryLog();

        // Import data using Laravel Excel
        $import = new CropDataImport();
        Excel::import($import, $this->filePath);

        // Re-enable query logging
        \DB::connection()->enableQueryLog();

        $recordCount = $import->getRecordsImported();
        $processingTime = round(microtime(true) - $startTime, 2);

        // Log the import activity
        AdminActivityLog::create([
            'admin_email' => $this->adminEmail,
            'action' => 'data_import',
            'action_type' => 'data_upload',
            'description' => "Imported {$recordCount} records from {$this->originalFileName} in {$processingTime}s",
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'metadata' => [
                'records_imported' => $recordCount,
                'processing_time' => $processingTime,
                'dataset_name' => $this->datasetName,
                'file_path' => $this->filePath
            ]
        ]);

        // Store metadata in database for ML to reference
        \DB::table('uploaded_datasets')->updateOrInsert(
            ['file_name' => basename($this->filePath)],
            [
                'name' => $this->datasetName,
                'file_name' => basename($this->filePath),
                'record_count' => $recordCount,
                'records_count' => $recordCount,
                'processing_time' => $processingTime,
                'status' => 'completed',
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Log the failure
        AdminActivityLog::create([
            'admin_email' => $this->adminEmail,
            'action' => 'data_import_failed',
            'action_type' => 'error',
            'description' => "Import failed for {$this->originalFileName}: {$exception->getMessage()}",
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'metadata' => [
                'error' => $exception->getMessage(),
                'dataset_name' => $this->datasetName,
                'file_path' => $this->filePath
            ]
        ]);

        // Update dataset status
        \DB::table('uploaded_datasets')->updateOrInsert(
            ['file_name' => basename($this->filePath)],
            [
                'name' => $this->datasetName,
                'file_name' => basename($this->filePath),
                'record_count' => 0,
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'updated_at' => now(),
            ]
        );
    }
}
