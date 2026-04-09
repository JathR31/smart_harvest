<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MigrateProductionSeeder extends Seeder
{
    /**
     * Backward-compatible alias for MigrateProductionDataSeeder.
     */
    public function run(): void
    {
        $this->call(MigrateProductionDataSeeder::class);
    }
}
