<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * user_id を nullable に変更
     * Webhook で user がまだ決まっていない場合（メールからのアクティベーション待ち）に対応
     */
    public function up(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['user_id']);

            // user_id を nullable に変更
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // 外部キー制約を再追加（nullable対応）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
