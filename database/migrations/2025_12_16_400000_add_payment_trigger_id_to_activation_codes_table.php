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
        Schema::table('activation_codes', function (Blueprint $table) {
            // payment_trigger_idカラムを追加（nullable）
            $table->unsignedBigInteger('payment_trigger_id')->nullable()->after('product_id');

            // 外部キー制約を追加
            $table->foreign('payment_trigger_id')->references('id')->on('payment_triggers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropForeign(['payment_trigger_id']);
            $table->dropColumn('payment_trigger_id');
        });
    }
};
