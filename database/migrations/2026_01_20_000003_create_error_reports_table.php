<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique()->comment('チケット番号 ER-YYYYMMDD-XXXXX');
            $table->string('error_type', 50)->comment('エラー種別: client_error, api_error, ssr_error, manual_report等');
            $table->string('status', 50)->default('new')->comment('ステータス: new, investigating, resolved, ignored');
            $table->string('severity', 20)->default('medium')->comment('重要度: low, medium, high, critical');
            $table->string('error_message', 500)->comment('エラーメッセージ');
            $table->text('stack_trace')->nullable()->comment('スタックトレース');
            $table->string('error_digest', 100)->nullable()->comment('Next.jsエラーダイジェスト');
            $table->string('url', 2000)->nullable()->comment('エラー発生URL');
            $table->string('endpoint', 500)->nullable()->comment('APIエンドポイント（API エラー時）');
            $table->integer('http_status')->nullable()->comment('HTTPステータスコード');
            $table->json('request_context')->nullable()->comment('リクエストコンテキスト（JSON）');
            $table->json('response_body')->nullable()->comment('レスポンスボディ（JSON）');
            $table->json('user_environment')->nullable()->comment('ユーザー環境情報（JSON）');
            $table->json('breadcrumbs')->nullable()->comment('操作履歴（JSON）');
            $table->string('error_hash', 64)->nullable()->comment('エラー重複検出用ハッシュ');
            $table->integer('occurrence_count')->default(1)->comment('発生回数');
            $table->timestamp('first_occurred_at')->nullable()->comment('初回発生日時');
            $table->timestamp('last_occurred_at')->nullable()->comment('最終発生日時');
            $table->string('ip_address', 45)->nullable()->comment('IPアドレス');
            $table->text('user_agent')->nullable()->comment('ユーザーエージェント');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('ログインユーザーID');
            $table->text('manual_description')->nullable()->comment('手動報告時の説明');
            $table->boolean('privacy_agreed')->default(true)->comment('プライバシーポリシー同意');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['error_type', 'status']);
            $table->index('severity');
            $table->index('error_hash');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_reports');
    }
};
