<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to ensure all required columns exist
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'google_avatar')) {
                $table->string('google_avatar')->nullable()->after('google_id');
            }
            if (!Schema::hasColumn('users', 'auth_method')) {
                $table->enum('auth_method', ['password', 'google'])->default('password')->after('google_avatar');
            }
            if (!Schema::hasColumn('users', 'password_set_at')) {
                $table->timestamp('password_set_at')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('Farmer')->after('password_set_at');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('location');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('users', 'farm_name')) {
                $table->string('farm_name')->nullable()->after('phone_verified_at');
            }
            if (!Schema::hasColumn('users', 'farm_size')) {
                $table->decimal('farm_size', 8, 2)->nullable()->after('farm_name');
            }
            if (!Schema::hasColumn('users', 'primary_crop')) {
                $table->string('primary_crop')->nullable()->after('farm_size');
            }
            if (!Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable()->after('primary_crop');
            }
            if (!Schema::hasColumn('users', 'otp_code')) {
                $table->string('otp_code')->nullable()->after('last_login');
            }
            if (!Schema::hasColumn('users', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns carefully
            $columnsToCheck = ['google_id', 'google_avatar', 'auth_method', 'password_set_at', 
                              'role', 'status', 'location', 'phone', 'phone_number', 'phone_verified_at',
                              'farm_name', 'farm_size', 'primary_crop', 'last_login', 'otp_code', 'otp_expires_at'];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('users', $column)) {
                    try {
                        if ($column === 'google_id') {
                            $table->dropUnique(['google_id']);
                        }
                        $table->dropColumn($column);
                    } catch (\Exception $e) {
                        // Column may not exist or may be needed, skip
                    }
                }
            }
        });
    }
};
