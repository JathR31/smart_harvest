<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add missing columns directly
     */
    public function up(): void
    {
        // Use raw SQL to safely add columns
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS google_id VARCHAR(255) UNIQUE NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS google_avatar VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS auth_method VARCHAR(50) DEFAULT 'password'");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS password_set_at TIMESTAMP NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(255) DEFAULT 'Farmer'");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS status VARCHAR(255) DEFAULT 'active'");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS location VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS phone_number VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS phone_verified_at TIMESTAMP NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS farm_name VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS farm_size DECIMAL(8,2) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS primary_crop VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS otp_code VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
        
        try {
            DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS otp_expires_at TIMESTAMP NULL");
        } catch (\Exception $e) {
            // Column might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop columns on rollback - they might be needed
    }
};
