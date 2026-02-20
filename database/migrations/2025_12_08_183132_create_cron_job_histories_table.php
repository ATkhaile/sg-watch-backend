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
        Schema::create('cron_job_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cron_job_id')->comment('CronジョブID');
            $table->timestamp('started_at')->comment('開始日時');
            $table->timestamp('finished_at')->nullable()->comment('終了日時');
            $table->integer('duration_ms')->nullable()->comment('実行時間（ミリ秒）');
            $table->tinyInteger('status')->comment('ステータス: 1=success, 2=failed, 3=timeout');
            $table->integer('response_code')->nullable()->comment('HTTPレスポンスコード');
            $table->text('response_body')->nullable()->comment('レスポンスボディ');
            $table->text('error_message')->nullable()->comment('エラーメッセージ');
            $table->json('request_data')->nullable()->comment('送信したリクエストデータ');
            $table->timestamps();

            $table->foreign('cron_job_id')->references('id')->on('cron_jobs')->onDelete('cascade');
            $table->index(['cron_job_id', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_job_histories');
    }
};
