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
        Schema::create('climate_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->decimal('avg_temperature', 5, 2); // in Celsius
            $table->decimal('min_temperature', 5, 2);
            $table->decimal('max_temperature', 5, 2);
            $table->decimal('rainfall', 8, 2); // in mm
            $table->decimal('humidity', 5, 2); // percentage
            $table->decimal('wind_speed', 5, 2)->nullable(); // in km/h
            $table->string('weather_condition')->nullable(); // Sunny, Rainy, Cloudy, etc.
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('municipality');
            $table->index('year');
            $table->index('month');
            $table->unique(['municipality', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climate_patterns');
    }
};
