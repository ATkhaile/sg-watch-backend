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
        Schema::create('chatbot_ai_connection_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id')->comment('Chatbot ID');
            $table->unsignedBigInteger('ai_application_id')->comment('AIアプリケーションID');
            $table->timestamp('connected_at')->comment('接続日時');
            $table->timestamp('disconnected_at')->nullable()->comment('切断日時');
            $table->boolean('is_current')->default(false)->comment('現在の接続かどうか');
            $table->timestamps();

            // 外部キー
            $table->foreign('chatbot_id')
                ->references('id')
                ->on('chatbots')
                ->onDelete('cascade');

            $table->foreign('ai_application_id')
                ->references('id')
                ->on('ai_applications')
                ->onDelete('cascade');

            // インデックス
            $table->index(['chatbot_id', 'is_current']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_ai_connection_history');
    }
};
