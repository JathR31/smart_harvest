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
        Schema::table('messages', function (Blueprint $table) {
            // Message threading
            $table->foreignId('parent_id')->nullable()->after('receiver_id')->constrained('messages')->onDelete('cascade');
            $table->string('conversation_id')->nullable()->after('parent_id')->index();
            
            // SMS integration
            $table->boolean('sent_as_sms')->default(false)->after('priority');
            $table->enum('sms_status', ['pending', 'sent', 'failed', 'not_sent'])->default('not_sent')->after('sent_as_sms');
            $table->text('sms_error')->nullable()->after('sms_status');
            
            // Additional metadata
            $table->boolean('is_replied')->default(false)->after('is_read');
            
            // Indexes for performance
            $table->index(['conversation_id', 'created_at']);
            $table->index(['parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['conversation_id', 'created_at']);
            $table->dropIndex(['parent_id']);
            
            $table->dropColumn([
                'parent_id',
                'conversation_id',
                'sent_as_sms',
                'sms_status',
                'sms_error',
                'is_replied'
            ]);
        });
    }
};
