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
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('crop_name');
            $table->string('variety')->nullable();
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('previous_price', 10, 2)->nullable();
            $table->enum('price_trend', ['up', 'down', 'stable'])->default('stable');
            $table->string('market_location')->default('La Trinidad Trading Post');
            $table->enum('demand_level', ['low', 'moderate', 'high', 'very_high'])->default('moderate');
            $table->date('price_date');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['crop_name', 'price_date']);
            $table->index(['is_active', 'price_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
