<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * activation_codes テーブルから payment_plans への外部キー制約を削除
     * payment_plan_id には plans テーブルの plan_id を格納するため
     */
    public function up(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            // payment_plans への外部キー制約を削除
            $table->dropForeign(['payment_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            // 外部キー制約を再追加（ロールバック用）
            $table->foreign('payment_plan_id')
                ->references('payment_plan_id')
                ->on('payment_plans')
                ->onDelete('cascade');
        });
    }
};
