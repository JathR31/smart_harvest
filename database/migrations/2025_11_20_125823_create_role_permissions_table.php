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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // Admin, Farmer, Field Agent, Researcher
            $table->string('permission'); // e.g., 'view_dashboard', 'manage_users', etc.
            $table->string('category')->nullable(); // Group permissions by category
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate role-permission combinations
            $table->unique(['role', 'permission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
