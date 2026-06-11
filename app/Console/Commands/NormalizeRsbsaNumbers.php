<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class NormalizeRsbsaNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:normalize-rsbsa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize existing users RSBSA numbers in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting RSBSA normalization...');

        $updated = 0;
        $skipped = 0;

        User::chunkById(100, function ($users) use (&$updated, &$skipped) {
            foreach ($users as $user) {
                $original = $user->rsbsa_number ?? '';
                $normalized = $original ? User::normalizeRsbsaNumber($original) : '';

                if (!$original && !$normalized) {
                    $skipped++;
                    continue;
                }

                if ($normalized !== $original) {
                    $user->rsbsa_number = $normalized ?: null;
                    $user->save();
                    $updated++;
                }
            }
        });

        $this->info("RSBSA normalization complete. Updated: {$updated}, Skipped (no value): {$skipped}");
        return Command::SUCCESS;
    }
}
