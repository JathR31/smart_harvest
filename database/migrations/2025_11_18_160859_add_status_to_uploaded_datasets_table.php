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
        Schema::table('uploaded_datasets', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('file_path'); // pending, processing, completed, failed
            $table->integer('records_count')->default(0)->after('status');
            $table->decimal('processing_time', 8, 2)->nullable()->after('records_count');
            $table->text('error_message')->nullable()->after('processing_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uploaded_datasets', function (Blueprint $table) {
            $table->dropColumn(['status', 'records_count', 'processing_time', 'error_message']);
        });
    }
};
