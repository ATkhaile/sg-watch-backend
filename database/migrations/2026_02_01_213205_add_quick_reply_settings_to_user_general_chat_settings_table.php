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
        Schema::table('user_general_chat_settings', function (Blueprint $table) {
            $table->boolean('quick_reply_enabled')->default(true)->after('friend_approval_required')->comment('クイック返信の表示ON/OFF');
            $table->boolean('quick_reply_default_open')->default(false)->after('quick_reply_enabled')->comment('クイック返信の初期表示状態（true=開く、false=閉じる）');
        });
    }

    public function down(): void
    {
        Schema::table('user_general_chat_settings', function (Blueprint $table) {
            $table->dropColumn(['quick_reply_enabled', 'quick_reply_default_open']);
        });
    }
};
