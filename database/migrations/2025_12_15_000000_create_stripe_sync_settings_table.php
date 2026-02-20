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
        Schema::create('stripe_sync_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id')->nullable()->comment('NULL=グローバル設定');
            $table->boolean('auto_sync_enabled')->default(false)->comment('自動同期ON/OFF');
            $table->boolean('webhook_enabled')->default(true)->comment('Webhook受信ON/OFF');
            $table->string('sync_frequency', 20)->default('6_hours')->comment('同期頻度: 30min, 1_hour, 6_hours, 12_hours, 1_day, 2_days, 3_days, 1_week');
            $table->timestamp('last_auto_sync_at')->nullable()->comment('最終自動同期日時');
            $table->timestamp('next_auto_sync_at')->nullable()->comment('次回自動同期予定日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')
                ->references('id')
                ->on('stripe_accounts')
                ->onDelete('cascade');

            $table->unique('stripe_account_id', 'unique_account_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_sync_settings');
    }
};