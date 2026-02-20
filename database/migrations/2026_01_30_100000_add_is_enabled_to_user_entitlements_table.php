<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * user_entitlements テーブルに is_enabled カラムを追加
     * メンバーシップ解除時にエンタイトルメントを無効化して残すオプション用
     */
    public function up(): void
    {
        Schema::table('user_entitlements', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(true)->after('is_overridden')
                  ->comment('エンタイトルメントの有効/無効（無効でも削除せずに残す）');
            $table->index('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_entitlements', function (Blueprint $table) {
            $table->dropIndex(['is_enabled']);
            $table->dropColumn('is_enabled');
        });
    }
};
