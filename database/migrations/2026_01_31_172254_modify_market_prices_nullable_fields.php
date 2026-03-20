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
        if (!Schema::hasTable('market_prices')) {
            return;
        }

        Schema::table('market_prices', function (Blueprint $table) {
            if (Schema::hasColumn('market_prices', 'price_per_kg')) {
                $table->decimal('price_per_kg', 10, 2)->nullable()->change();
            }

            if (Schema::hasColumn('market_prices', 'price_date')) {
                $table->date('price_date')->nullable()->change();
            }

            if (Schema::hasColumn('market_prices', 'created_by')) {
                $table->foreignId('created_by')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('market_prices')) {
            return;
        }

        Schema::table('market_prices', function (Blueprint $table) {
            if (Schema::hasColumn('market_prices', 'price_per_kg')) {
                $table->decimal('price_per_kg', 10, 2)->nullable(false)->change();
            }

            if (Schema::hasColumn('market_prices', 'price_date')) {
                $table->date('price_date')->nullable(false)->change();
            }

            if (Schema::hasColumn('market_prices', 'created_by')) {
                $table->foreignId('created_by')->nullable(false)->change();
            }
        });
    }
};
