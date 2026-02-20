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
        // Add reply_to_message_id to chat_messages (user-to-user chat)
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to_message_id')->nullable()->after('receiver_id');
            $table->foreign('reply_to_message_id')->references('id')->on('chat_messages')->onDelete('set null');
        });

        // Add reply_to_message_id to chat_group_messages (group chat)
        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to_message_id')->nullable()->after('group_id');
            $table->foreign('reply_to_message_id')->references('id')->on('chat_group_messages')->onDelete('set null');
        });

        // Create message_mentions table for user-to-user chat mentions
        Schema::create('message_mentions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('mentioned_user_id');
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('chat_messages')->onDelete('cascade');
            $table->foreign('mentioned_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['message_id', 'mentioned_user_id']);
        });

        // Create group_message_mentions table for group chat mentions
        Schema::create('group_message_mentions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('mentioned_user_id');
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('chat_group_messages')->onDelete('cascade');
            $table->foreign('mentioned_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['message_id', 'mentioned_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_message_mentions');
        Schema::dropIfExists('message_mentions');

        Schema::table('chat_group_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_message_id']);
            $table->dropColumn('reply_to_message_id');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_message_id']);
            $table->dropColumn('reply_to_message_id');
        });
    }
};
