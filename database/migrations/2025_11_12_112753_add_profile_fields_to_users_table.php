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
            $table->string('phone')->nullable()->after('email');
            $table->string('location')->nullable()->after('phone');
            $table->string('farm_name')->nullable()->after('location');
            $table->decimal('farm_size', 8, 2)->nullable()->after('farm_name');
            $table->string('crop_types')->nullable()->after('farm_size');
            $table->integer('years_experience')->nullable()->after('crop_types');
            $table->text('bio')->nullable()->after('years_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'location',
                'farm_name',
                'farm_size',
                'crop_types',
                'years_experience',
                'bio'
            ]);
        });
    }
};
