<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all seeders in order
        $this->call([
            SampleUsersSeeder::class,
            CropDataSeeder::class,
            ClimatePatternSeeder::class,
        ]);
        
        $this->command->info('All seeders completed successfully!');
    }
}
