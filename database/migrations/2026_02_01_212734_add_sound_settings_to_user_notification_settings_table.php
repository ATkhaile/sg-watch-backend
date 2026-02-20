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
        Schema::table('user_notification_settings', function (Blueprint $table) {
            $table->boolean('receive_sound_enabled')->default(true)->after('reminder_timing')->comment('受信通知時の効果音（着信音）');
            $table->boolean('send_sound_enabled')->default(true)->after('receive_sound_enabled')->comment('メッセージ送信時の効果音');
        });
    }

    public function down(): void
    {
        Schema::table('user_notification_settings', function (Blueprint $table) {
            $table->dropColumn(['receive_sound_enabled', 'send_sound_enabled']);
        });
    }
};
