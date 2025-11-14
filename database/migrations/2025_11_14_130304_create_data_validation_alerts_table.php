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
        Schema::create('data_validation_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('record_id')->unique();
            $table->text('issue_description');
            $table->enum('status', ['Pending', 'Resolved', 'Ignored'])->default('Pending');
            $table->enum('severity', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->unsignedBigInteger('related_user_id')->nullable();
            $table->json('data_snapshot')->nullable(); // Store problematic data
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('severity');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_validation_alerts');
    }
};
