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
        // Drop existing foreign keys with CASCADE
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['video_call_session_id']);
        });

        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->dropForeign(['video_call_session_id']);
        });

        // Re-add foreign keys with SET NULL instead of CASCADE
        // This ensures chat history is preserved even when video call session is deleted
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->foreign('video_call_session_id')
                ->references('id')
                ->on('video_call_sessions')
                ->onDelete('set null');
        });

        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->foreign('video_call_session_id')
                ->references('id')
                ->on('video_call_sessions')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to CASCADE
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['video_call_session_id']);
        });

        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->dropForeign(['video_call_session_id']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->foreign('video_call_session_id')
                ->references('id')
                ->on('video_call_sessions')
                ->onDelete('cascade');
        });

        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->foreign('video_call_session_id')
                ->references('id')
                ->on('video_call_sessions')
                ->onDelete('cascade');
        });
    }
};
