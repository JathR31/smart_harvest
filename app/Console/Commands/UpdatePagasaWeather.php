<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PagasaWeatherService;

class UpdatePagasaWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:update-pagasa
                          {--force : Force update even if recently updated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update weather data from PAGASA';

    protected $pagasaService;

    /**
     * Create a new command instance.
     */
    public function __construct(PagasaWeatherService $pagasaService)
    {
        parent::__construct();
        $this->pagasaService = $pagasaService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting PAGASA weather data update...');
        
        $startTime = microtime(true);
        
        $result = $this->pagasaService->updateWeatherData();
        
        $duration = round(microtime(true) - $startTime, 2);

        if ($result) {
            $this->info("Weather data updated successfully in {$duration} seconds");
            return Command::SUCCESS;
        } else {
            $this->error("Failed to update weather data after {$duration} seconds");
            return Command::FAILURE;
        }
    }
}
