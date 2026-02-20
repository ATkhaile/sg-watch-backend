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
        Schema::create('user_general_chat_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->boolean('read_receipts_enabled')->default(true)->comment('既読表示を有効にする');
            $table->boolean('typing_indicator_enabled')->default(true)->comment('入力中表示を有効にする');
            $table->boolean('friend_approval_required')->default(false)->comment('フレンド追加を承認制にする');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_general_chat_settings');
    }
};
