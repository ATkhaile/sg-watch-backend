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
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->boolean('new_message_enabled')->default(true)->comment('新着メッセージ通知');
            $table->boolean('pinned_user_enabled')->default(true)->comment('ピン留めユーザー通知');
            $table->boolean('thread_reply_enabled')->default(true)->comment('スレッドへの返信通知');
            $table->boolean('unread_reminder_enabled')->default(true)->comment('未読リマインダー');
            $table->enum('reminder_timing', ['24h', '48h', '72h'])->default('24h')->comment('リマインドのタイミング');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_settings');
    }
};
