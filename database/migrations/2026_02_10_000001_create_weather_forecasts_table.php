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
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->text('weather_condition');
            $table->string('wind_condition');
            $table->string('temp_high_range')->nullable();
            $table->string('temp_low_range')->nullable();
            $table->string('humidity_range')->nullable();
            $table->string('rainfall_range')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('fwfa_number')->nullable();
            $table->date('forecast_date');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            
            $table->index('forecast_date');
            $table->index('region');
        });

        Schema::create('soil_moisture_data', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->string('province')->nullable();
            $table->enum('condition', ['wet', 'moist', 'dry']);
            $table->date('observation_date');
            $table->timestamps();
            
            $table->index('municipality');
            $table->index('observation_date');
        });

        Schema::create('farming_advisories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('applicable_regions')->nullable();
            $table->enum('severity', ['info', 'warning', 'critical'])->default('info');
            $table->date('advisory_date');
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            
            $table->index('advisory_date');
        });

        Schema::create('enso_alerts', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['la_nina', 'el_nino', 'neutral', 'watch']);
            $table->text('description')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('alert_date');
            $table->date('updated_date')->nullable();
            $table->timestamps();
            
            $table->index('alert_date');
        });

        Schema::create('gale_warnings', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->text('description');
            $table->enum('severity', ['moderate', 'strong', 'gale', 'storm'])->default('moderate');
            $table->text('affected_municipalities')->nullable();
            $table->date('warning_date');
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
            
            $table->index('warning_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gale_warnings');
        Schema::dropIfExists('enso_alerts');
        Schema::dropIfExists('farming_advisories');
        Schema::dropIfExists('soil_moisture_data');
        Schema::dropIfExists('weather_forecasts');
    }
};
