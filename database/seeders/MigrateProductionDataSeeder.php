<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Crop;
use App\Models\MarketPrice;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MigrateProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder migrates all important data from local development
     * to production. It's version-controlled so changes can be tracked
     * in git for easy syncing across environments.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow data insertion
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // 1. Migrate Users
            $this->migrateUsers();

            // 2. Migrate Crops
            $this->migrateCrops();

            // 3. Migrate Market Prices
            $this->migrateMarketPrices();

            // 4. Migrate Announcements
            $this->migrateAnnouncements();

            echo "\n✅ Data migration completed successfully!\n";
        } catch (\Exception $e) {
            echo "\n❌ Migration error: " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Migrate users from source database
     */
    private function migrateUsers(): void
    {
        echo "Migrating users...";

        // Get all users from current database
        $users = User::all();

        if ($users->isEmpty()) {
            echo " (No users found)\n";
            return;
        }

        foreach ($users as $user) {
            // Check if user already exists in target (by email)
            $exists = User::where('email', $user->email)->exists();

            if (!$exists) {
                User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'password' => $user->password, // Already hashed
                    'phone_number' => $user->phone_number ?? null,
                    'role' => $user->role ?? 'farmer',
                    'is_active' => $user->is_active ?? true,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
        }

        echo " Done! (" . $users->count() . " users)\n";
    }

    /**
     * Migrate crops from source database
     */
    private function migrateCrops(): void
    {
        echo "Migrating crops...";

        $crops = Crop::all();

        if ($crops->isEmpty()) {
            echo " (No crops found)\n";
            return;
        }

        foreach ($crops as $crop) {
            $exists = Crop::where('farmer_id', $crop->farmer_id)
                ->where('crop_name', $crop->crop_name)
                ->exists();

            if (!$exists) {
                Crop::create([
                    'farmer_id' => $crop->farmer_id,
                    'crop_name' => $crop->crop_name,
                    'area_planted' => $crop->area_planted ?? null,
                    'planting_date' => $crop->planting_date ?? null,
                    'expected_harvest_date' => $crop->expected_harvest_date ?? null,
                    'status' => $crop->status ?? 'growing',
                    'municipality' => $crop->municipality ?? null,
                    'variety' => $crop->variety ?? null,
                    'created_at' => $crop->created_at,
                    'updated_at' => $crop->updated_at,
                ]);
            }
        }

        echo " Done! (" . $crops->count() . " crops)\n";
    }

    /**
     * Migrate market prices from source database
     */
    private function migrateMarketPrices(): void
    {
        echo "Migrating market prices...";

        $prices = MarketPrice::all();

        if ($prices->isEmpty()) {
            echo " (No market prices found)\n";
            return;
        }

        foreach ($prices as $price) {
            $exists = MarketPrice::where('crop_name', $price->crop_name)
                ->where('market', $price->market ?? 'General')
                ->whereDate('date', $price->date->toDateString())
                ->exists();

            if (!$exists) {
                MarketPrice::create([
                    'crop_name' => $price->crop_name,
                    'market' => $price->market ?? 'General',
                    'price' => $price->price,
                    'date' => $price->date,
                    'unit' => $price->unit ?? 'kg',
                    'created_at' => $price->created_at,
                    'updated_at' => $price->updated_at,
                ]);
            }
        }

        echo " Done! (" . $prices->count() . " prices)\n";
    }

    /**
     * Migrate announcements from source database
     */
    private function migrateAnnouncements(): void
    {
        echo "Migrating announcements...";

        $announcements = Announcement::all();

        if ($announcements->isEmpty()) {
            echo " (No announcements found)\n";
            return;
        }

        foreach ($announcements as $announcement) {
            $exists = Announcement::where('title', $announcement->title)
                ->where('user_id', $announcement->user_id)
                ->exists();

            if (!$exists) {
                Announcement::create([
                    'user_id' => $announcement->user_id,
                    'title' => $announcement->title,
                    'content' => $announcement->content ?? '',
                    'is_published' => $announcement->is_published ?? false,
                    'created_at' => $announcement->created_at,
                    'updated_at' => $announcement->updated_at,
                ]);
            }
        }

        echo " Done! (" . $announcements->count() . " announcements)\n";
    }
}
