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
        Schema::table('market_prices', function (Blueprint $table) {
            $table->decimal('price_per_kg', 10, 2)->nullable()->change();
            $table->date('price_date')->nullable()->change();
            $table->foreignId('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('market_prices', function (Blueprint $table) {
            $table->decimal('price_per_kg', 10, 2)->nullable(false)->change();
            $table->date('price_date')->nullable(false)->change();
            $table->foreignId('created_by')->nullable(false)->change();
        });
    }
};
