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
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('UUID識別子');
            $table->unsignedBigInteger('chatbot_id')->comment('Chatbot ID');
            $table->unsignedBigInteger('user_id')->comment('ユーザーID');
            $table->unsignedBigInteger('ai_conversation_id')->nullable()->comment('AI会話ID (履歴Bへのリンク)');
            $table->string('title', 255)->nullable()->comment('会話タイトル');
            $table->timestamps();
            $table->softDeletes();

            // 外部キー
            $table->foreign('chatbot_id')
                ->references('id')
                ->on('chatbots')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('ai_conversation_id')
                ->references('id')
                ->on('ai_message_conversations')
                ->onDelete('set null');

            // インデックス
            $table->index(['chatbot_id', 'user_id']);
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
    }
};
