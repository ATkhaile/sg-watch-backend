<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique()->comment('チケット番号 CT-YYYYMMDD-XXXXX');
            $table->string('type', 50)->default('contact')->comment('メッセージ種別: contact, inquiry, feedback等');
            $table->string('status', 50)->default('pending')->comment('ステータス: pending, in_progress, resolved, closed');
            $table->string('subject', 200)->comment('件名');
            $table->text('body')->comment('本文');
            $table->string('reply_email', 255)->nullable()->comment('返信先メールアドレス');
            $table->string('organization', 100)->comment('所属（会社名・団体名）');
            $table->string('name', 100)->comment('名前');
            $table->json('metadata')->nullable()->comment('追加メタデータ（JSON）');
            $table->string('ip_address', 45)->nullable()->comment('IPアドレス');
            $table->text('user_agent')->nullable()->comment('ユーザーエージェント');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('ログインユーザーID');
            $table->boolean('privacy_agreed')->default(true)->comment('プライバシーポリシー同意');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
