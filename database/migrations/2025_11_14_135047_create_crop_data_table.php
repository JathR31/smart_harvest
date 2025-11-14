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
        Schema::create('crop_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('crop_type'); // e.g., Cabbage, Carrot, Potato
            $table->string('variety')->nullable(); // crop variety
            $table->string('municipality'); // Location
            $table->decimal('area_planted', 10, 2); // in hectares
            $table->decimal('yield_amount', 10, 2)->nullable(); // in kg or tons
            $table->date('planting_date');
            $table->date('harvest_date')->nullable();
            $table->enum('status', ['Planning', 'Planted', 'Growing', 'Harvested', 'Failed'])->default('Planning');
            $table->decimal('temperature', 5, 2)->nullable(); // in Celsius
            $table->decimal('rainfall', 8, 2)->nullable(); // in mm
            $table->decimal('humidity', 5, 2)->nullable(); // percentage
            $table->text('notes')->nullable();
            $table->enum('validation_status', ['Pending', 'Validated', 'Flagged'])->default('Pending');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('municipality');
            $table->index('status');
            $table->index('validation_status');
            $table->index('planting_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_data');
    }
};
