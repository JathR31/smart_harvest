<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // DA Admin-specific fields
            $table->string('office')->nullable()->after('farm_name');
            $table->string('position')->nullable()->after('office');
            $table->string('employee_id')->nullable()->after('position');
            $table->string('primary_crop')->nullable()->after('farm_size');
            $table->json('admin_permissions')->nullable()->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'office',
                'position',
                'employee_id',
                'primary_crop',
                'admin_permissions'
            ]);
        });
    }
};
