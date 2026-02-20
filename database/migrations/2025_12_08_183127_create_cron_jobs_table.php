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
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Cronジョブ名');
            $table->string('url', 2048)->comment('呼び出すURL');
            $table->text('description')->nullable()->comment('説明');
            $table->string('method')->default('GET')->comment('HTTPメソッド: GET, POST, PUT, DELETE');
            $table->json('headers')->nullable()->comment('カスタムヘッダー');
            $table->json('body')->nullable()->comment('リクエストボディ（POST/PUT用）');
            $table->tinyInteger('frequency_type')->nullable()->comment('スケジュールタイプ: 1=minute, 2=hour, 3=day, 4=month, 5=year, 6=custom');
            $table->integer('frequency_value')->nullable()->comment('頻度値（例：5分ごと）');
            $table->string('cron_expression')->nullable()->comment('カスタムスケジュール用のCron式');
            $table->json('scheduled_dates')->nullable()->comment('不定期実行用の指定日時配列 ["2025-12-25 10:00:00", "2025-12-31 23:59:00"]'); // V1対応
            $table->tinyInteger('status')->default(1)->comment('ステータス: 1=active, 2=inactive');
            $table->timestamp('last_run_at')->nullable()->comment('最終実行日時');
            $table->timestamp('next_run_at')->nullable()->comment('次回実行日時');
            $table->integer('total_runs')->default(0)->comment('総実行回数');
            $table->integer('success_count')->default(0)->comment('成功回数');
            $table->integer('failure_count')->default(0)->comment('失敗回数');
            $table->integer('timeout')->default(30)->comment('タイムアウト（秒）');
            $table->unsignedBigInteger('created_by')->nullable()->comment('作成者ユーザーID');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'next_run_at']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_jobs');
    }
};
