<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Backfills conversation_id for messages created before threading existed
 * (i.e. before 2026_02_28_000001_add_threading_and_sms_to_messages_table).
 *
 * Those legacy rows have conversation_id = NULL, which is dangerous:
 * Laravel rewrites where('conversation_id', null) into whereNull(...), so
 * MessageController::show() would match every legacy NULL-conversation
 * message across every user pair, not just the intended conversation.
 *
 * Root messages (parent_id IS NULL) each get their own new conversation_id,
 * mirroring exactly what Message::boot()'s creating() hook already does for
 * new root messages. Replies inherit their root's conversation_id, since
 * MessageController::reply() always stores parent_id as the resolved TRUE
 * root's id (never an intermediate reply) - the data is always a flat
 * two-level tree, so no chain-walking is needed.
 *
 * Uses DB::table() (not the Eloquent model) so soft-deleted legacy rows are
 * included (SoftDeletes' global scope only applies to Eloquent queries).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('messages')
            ->whereNull('parent_id')
            ->whereNull('conversation_id')
            ->select('id')
            ->chunkById(500, function ($roots) {
                foreach ($roots as $root) {
                    DB::table('messages')
                        ->where('id', $root->id)
                        ->update(['conversation_id' => (string) Str::uuid()]);
                }
            });

        DB::table('messages')
            ->whereNotNull('parent_id')
            ->whereNull('conversation_id')
            ->select('id', 'parent_id')
            ->chunkById(500, function ($replies) {
                foreach ($replies as $reply) {
                    $rootConversationId = DB::table('messages')
                        ->where('id', $reply->parent_id)
                        ->value('conversation_id');

                    // Defensive fallback if the parent is missing/deleted.
                    $rootConversationId = $rootConversationId ?: (string) Str::uuid();

                    DB::table('messages')
                        ->where('id', $reply->id)
                        ->update(['conversation_id' => $rootConversationId]);
                }
            });

        // No row can be NULL now - enforce it at the schema level so this
        // bug class can never reoccur. Plain Blueprint call, safe on both
        // sqlite and postgres (Laravel 12 rebuilds the column natively).
        Schema::table('messages', function (Blueprint $table) {
            $table->string('conversation_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        // Backfilled values are synthetic identifiers with no meaningful
        // "NULL" state worth restoring - not reversible beyond the column.
        Schema::table('messages', function (Blueprint $table) {
            $table->string('conversation_id')->nullable()->change();
        });
    }
};
