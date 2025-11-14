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
            $table->enum('role', ['Farmer', 'Field Agent', 'Admin', 'Researcher'])->default('Farmer')->after('email');
            $table->enum('status', ['Active', 'Pending', 'Suspended'])->default('Active')->after('role');
            $table->timestamp('last_login')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'last_login']);
        });
    }
};
