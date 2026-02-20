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
        Schema::create('auto_reply_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 100)->nullable()->comment('設定名（例: 営業時間外、ランチタイム）');
            $table->boolean('enabled')->default(true)->comment('この設定を有効にする');
            $table->time('start_time')->comment('自動返信開始時刻（例: 18:00）');
            $table->time('end_time')->comment('自動返信終了時刻（例: 09:00）');
            $table->text('message')->comment('自動返信メッセージ');
            $table->json('days_of_week')->nullable()->comment('適用曜日 [0=日,1=月,...,6=土] 例: [1,2,3,4,5]');
            $table->integer('priority')->default(0)->comment('優先度（数字が大きいほど優先）');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'enabled', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_reply_settings');
    }
};
