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
        Schema::create('chatbots', function (Blueprint $table) {
            $table->id();
            $table->string('chatbot_id', 100)->unique()->comment('固定識別子 (例: community-chatbot-demo-1)');
            $table->unsignedBigInteger('ai_application_id')->nullable()->comment('接続中のAIアプリケーションID');

            // 表示設定
            $table->string('display_name', 255)->comment('表示名');
            $table->text('description')->nullable()->comment('説明');
            $table->text('welcome_message')->nullable()->comment('ウェルカムメッセージ');
            $table->string('color', 50)->default('emerald')->comment('テーマカラー');
            $table->boolean('sound_notification')->default(true)->comment('音声通知');

            // メタ
            $table->boolean('is_active')->default(true)->comment('有効フラグ');
            $table->timestamps();
            $table->softDeletes();

            // 外部キー
            $table->foreign('ai_application_id')
                ->references('id')
                ->on('ai_applications')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};
