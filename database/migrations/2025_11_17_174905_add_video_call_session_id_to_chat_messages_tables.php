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
        // Add video_call_session_id to chat_messages table
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('video_call_session_id')->nullable()->after('message_type');
            $table->foreign('video_call_session_id')->references('id')->on('video_call_sessions')->onDelete('cascade');
        });

        // Add video_call_session_id to chat_group_messages table
        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('video_call_session_id')->nullable()->after('message_type');
            $table->foreign('video_call_session_id')->references('id')->on('video_call_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['video_call_session_id']);
            $table->dropColumn('video_call_session_id');
        });

        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->dropForeign(['video_call_session_id']);
            $table->dropColumn('video_call_session_id');
        });
    }
};
