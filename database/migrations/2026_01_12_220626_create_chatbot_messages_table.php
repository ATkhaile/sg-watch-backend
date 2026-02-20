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
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->comment('会話ID');
            $table->unsignedBigInteger('ai_message_id')->nullable()->comment('AIメッセージID (履歴Bへのリンク)');
            $table->enum('role', ['user', 'assistant'])->comment('メッセージ送信者');
            $table->text('content')->comment('メッセージ内容');
            $table->timestamps();
            $table->softDeletes();

            // 外部キー
            $table->foreign('conversation_id')
                ->references('id')
                ->on('chatbot_conversations')
                ->onDelete('cascade');

            $table->foreign('ai_message_id')
                ->references('id')
                ->on('ai_app_messages')
                ->onDelete('set null');

            // インデックス
            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_messages');
    }
};
