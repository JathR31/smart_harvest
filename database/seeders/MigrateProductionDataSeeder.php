<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CropData;
use App\Models\MarketPrice;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

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
        $driver = DB::connection()->getDriverName();
        $foreignKeysTemporarilyDisabled = false;

        // MySQL supports session-level FK toggle. PostgreSQL on managed hosts often does not.
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $foreignKeysTemporarilyDisabled = true;
        }

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
            if ($foreignKeysTemporarilyDisabled) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
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

        $crops = CropData::all();

        if ($crops->isEmpty()) {
            echo " (No crops found)\n";
            return;
        }

        foreach ($crops as $crop) {
            $exists = CropData::where('user_id', $crop->user_id)
                ->where('crop_type', $crop->crop_type)
                ->whereDate('planting_date', $crop->planting_date)
                ->exists();

            if (!$exists) {
                CropData::create([
                    'user_id' => $crop->user_id,
                    'crop_type' => $crop->crop_type,
                    'variety' => $crop->variety,
                    'municipality' => $crop->municipality,
                    'area_planted' => $crop->area_planted ?? null,
                    'yield_amount' => $crop->yield_amount ?? null,
                    'planting_date' => $crop->planting_date,
                    'harvest_date' => $crop->harvest_date,
                    'status' => $crop->status ?? 'Planning',
                    'temperature' => $crop->temperature,
                    'rainfall' => $crop->rainfall,
                    'humidity' => $crop->humidity,
                    'notes' => $crop->notes,
                    'validation_status' => $crop->validation_status ?? 'Pending',
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

        $defaultCreatorId = User::whereIn('role', ['DA Admin', 'Admin'])->value('id')
            ?? User::value('id');

        if ($prices->isEmpty()) {
            echo " (No market prices found)\n";
            return;
        }

        if (!$defaultCreatorId) {
            echo " (Skipped: no users available for created_by)\n";
            return;
        }

        foreach ($prices as $price) {
            $resolvedDate = $price->price_date ? $price->price_date->toDateString() : now()->toDateString();
            $exists = MarketPrice::where('crop_name', $price->crop_name)
                ->whereDate('price_date', $resolvedDate)
                ->exists();

            if (!$exists) {
                MarketPrice::create([
                    'created_by' => $price->created_by ?? $defaultCreatorId,
                    'crop_name' => $price->crop_name,
                    'variety' => $price->variety,
                    'price_per_kg' => $price->price_per_kg ?? 0,
                    'previous_price' => $price->previous_price,
                    'price_trend' => $price->price_trend ?? 'stable',
                    'market_location' => $price->market_location ?? 'La Trinidad Trading Post',
                    'demand_level' => $price->demand_level ?? 'moderate',
                    'price_date' => $resolvedDate,
                    'notes' => $price->notes,
                    'is_active' => $price->is_active ?? true,
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

        $defaultCreatorId = User::whereIn('role', ['DA Admin', 'Admin'])->value('id')
            ?? User::value('id');

        if ($announcements->isEmpty()) {
            echo " (No announcements found)\n";
            return;
        }

        if (!$defaultCreatorId) {
            echo " (Skipped: no users available for created_by)\n";
            return;
        }

        foreach ($announcements as $announcement) {
            $creatorId = $announcement->created_by ?? $defaultCreatorId;
            $exists = Announcement::where('title', $announcement->title)
                ->where('created_by', $creatorId)
                ->exists();

            if (!$exists) {
                Announcement::create([
                    'created_by' => $creatorId,
                    'title' => $announcement->title,
                    'content' => $announcement->content ?? '',
                    'type' => $announcement->type ?? 'general',
                    'priority' => $announcement->priority ?? 'normal',
                    'is_active' => $announcement->is_active ?? true,
                    'published_at' => $announcement->published_at,
                    'expires_at' => $announcement->expires_at,
                    'target_municipalities' => $announcement->target_municipalities,
                    'created_at' => $announcement->created_at,
                    'updated_at' => $announcement->updated_at,
                ]);
            }
        }

        echo " Done! (" . $announcements->count() . " announcements)\n";
    }
}
