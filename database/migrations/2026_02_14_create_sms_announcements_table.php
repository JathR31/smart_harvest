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
        Schema::create('sms_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->enum('recipient_type', ['all', 'selected', 'municipality'])->default('all');
            $table->string('recipient_filter')->nullable(); // municipality name or other filter
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->enum('status', ['pending', 'sent', 'partial', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->json('details')->nullable(); // detailed results per recipient
            $table->timestamps();
            
            $table->index('sender_id');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_announcements');
    }
};
