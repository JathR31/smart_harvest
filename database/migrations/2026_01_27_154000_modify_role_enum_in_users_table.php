<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the role enum to include 'DA Admin'
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('Farmer', 'Field Agent', 'Admin', 'Researcher', 'DA Admin', 'Regional Manager', 'Extension Officer') DEFAULT 'Farmer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('Farmer', 'Field Agent', 'Admin', 'Researcher') DEFAULT 'Farmer'");
    }
};
