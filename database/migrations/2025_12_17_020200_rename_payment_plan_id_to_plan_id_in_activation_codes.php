<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * activation_codes テーブルの payment_plan_id を plan_id にリネーム
     * 新しいシステムでは plans テーブルの plan_id を格納するため
     */
    public function up(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            // インデックスを削除
            $table->dropIndex('activation_codes_payment_plan_id_foreign');

            // カラム名を変更
            $table->renameColumn('payment_plan_id', 'plan_id');
        });

        // 新しいインデックスを追加
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->index('plan_id', 'activation_codes_plan_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropIndex('activation_codes_plan_id_index');
            $table->renameColumn('plan_id', 'payment_plan_id');
            $table->index('payment_plan_id', 'activation_codes_payment_plan_id_foreign');
        });
    }
};
