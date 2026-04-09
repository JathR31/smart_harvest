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
    private const CHUNK_SIZE = 500;

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

        // Prevent memory growth from accumulated SQL logs during large migrations.
        DB::connection()->disableQueryLog();

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

        $processed = 0;
        $inserted = 0;

        User::query()->chunkById(self::CHUNK_SIZE, function ($users) use (&$processed, &$inserted) {
            foreach ($users as $user) {
                $processed++;

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
                    $inserted++;
                }
            }
        });

        if ($processed === 0) {
            echo " (No users found)\n";
            return;
        }

        echo " Done! (processed: {$processed}, inserted: {$inserted})\n";
    }

    /**
     * Migrate crops from source database
     */
    private function migrateCrops(): void
    {
        echo "Migrating crops...";

        $processed = 0;
        $inserted = 0;

        CropData::query()->chunkById(self::CHUNK_SIZE, function ($crops) use (&$processed, &$inserted) {
            foreach ($crops as $crop) {
                $processed++;

                $existsQuery = CropData::where('user_id', $crop->user_id)
                    ->where('crop_type', $crop->crop_type);

                if ($crop->planting_date) {
                    $existsQuery->whereDate('planting_date', $crop->planting_date);
                } else {
                    $existsQuery->whereNull('planting_date');
                }

                $exists = $existsQuery->exists();

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
                    $inserted++;
                }
            }
        });

        if ($processed === 0) {
            echo " (No crops found)\n";
            return;
        }

        echo " Done! (processed: {$processed}, inserted: {$inserted})\n";
    }

    /**
     * Migrate market prices from source database
     */
    private function migrateMarketPrices(): void
    {
        echo "Migrating market prices...";

        $defaultCreatorId = User::whereIn('role', ['DA Admin', 'Admin'])->value('id')
            ?? User::value('id');

        if (!$defaultCreatorId) {
            echo " (Skipped: no users available for created_by)\n";
            return;
        }

        $processed = 0;
        $inserted = 0;

        MarketPrice::query()->chunkById(self::CHUNK_SIZE, function ($prices) use (&$processed, &$inserted, $defaultCreatorId) {
            foreach ($prices as $price) {
                $processed++;

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
                    $inserted++;
                }
            }
        });

        if ($processed === 0) {
            echo " (No market prices found)\n";
            return;
        }

        echo " Done! (processed: {$processed}, inserted: {$inserted})\n";
    }

    /**
     * Migrate announcements from source database
     */
    private function migrateAnnouncements(): void
    {
        echo "Migrating announcements...";

        $defaultCreatorId = User::whereIn('role', ['DA Admin', 'Admin'])->value('id')
            ?? User::value('id');

        if (!$defaultCreatorId) {
            echo " (Skipped: no users available for created_by)\n";
            return;
        }

        $processed = 0;
        $inserted = 0;

        Announcement::query()->chunkById(self::CHUNK_SIZE, function ($announcements) use (&$processed, &$inserted, $defaultCreatorId) {
            foreach ($announcements as $announcement) {
                $processed++;

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
                    $inserted++;
                }
            }
        });

        if ($processed === 0) {
            echo " (No announcements found)\n";
            return;
        }

        echo " Done! (processed: {$processed}, inserted: {$inserted})\n";
    }
}
