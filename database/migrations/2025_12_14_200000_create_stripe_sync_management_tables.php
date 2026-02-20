<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 同期管理テーブル (4テーブル)
     */
    public function up(): void
    {
        // 1. stripe_webhook_events - Webhookイベント記録
        Schema::create('stripe_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('event_id', 100)->comment('Stripe Event ID');
            $table->string('type', 100)->comment('イベントタイプ (e.g., customer.created)');
            $table->json('data_object')->nullable()->comment('イベントデータ');
            $table->timestamp('received_at')->nullable()->comment('受信日時');
            $table->boolean('processed_flag')->default(false)->comment('処理済みフラグ');
            $table->timestamp('processed_at')->nullable()->comment('処理日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'event_id']);
            $table->index(['stripe_account_id', 'type']);
            $table->index(['processed_flag', 'received_at']);
        });

        // 2. stripe_sync_states - 同期状態管理
        Schema::create('stripe_sync_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('object_name', 100)->comment('オブジェクト名 (e.g., customers, products)');
            $table->string('last_synced_id')->nullable()->comment('最後に同期したStripe ID');
            $table->timestamp('last_synced_at')->nullable()->comment('最終同期日時');
            $table->string('cursor')->nullable()->comment('ページネーション用カーソル');
            $table->integer('total_count')->default(0)->comment('同期済み総数');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'object_name']);
        });

        // 3. stripe_sync_jobs - 同期ジョブ履歴
        Schema::create('stripe_sync_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('object_name', 100)->comment('オブジェクト名');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed', 'cancelled'])->default('pending')->comment('ステータス');
            $table->enum('job_type', ['backfill', 'incremental', 'webhook', 'manual', 'auto'])->default('manual')->comment('ジョブタイプ');
            $table->timestamp('started_at')->nullable()->comment('開始日時');
            $table->timestamp('finished_at')->nullable()->comment('終了日時');
            $table->integer('processed_count')->default(0)->comment('処理件数');
            $table->integer('error_count')->default(0)->comment('エラー件数');
            $table->text('message')->nullable()->comment('メッセージ');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->index(['stripe_account_id', 'object_name', 'status']);
            $table->index(['status', 'started_at']);
        });

        // 4. stripe_sync_errors - 同期エラー記録
        Schema::create('stripe_sync_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->unsignedBigInteger('job_id')->nullable()->comment('関連ジョブID');
            $table->string('object_name', 100)->comment('オブジェクト名');
            $table->string('stripe_object_id')->nullable()->comment('対象のStripe ID');
            $table->string('error_type', 100)->comment('エラータイプ');
            $table->text('error_message')->comment('エラーメッセージ');
            $table->json('error_context')->nullable()->comment('エラーコンテキスト');
            $table->timestamp('occurred_at')->comment('発生日時');
            $table->boolean('resolved_flag')->default(false)->comment('解決済みフラグ');
            $table->timestamp('resolved_at')->nullable()->comment('解決日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('stripe_sync_jobs')->onDelete('set null');
            $table->index(['stripe_account_id', 'object_name']);
            $table->index(['resolved_flag', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_sync_errors');
        Schema::dropIfExists('stripe_sync_jobs');
        Schema::dropIfExists('stripe_sync_states');
        Schema::dropIfExists('stripe_webhook_events');
    }
};
