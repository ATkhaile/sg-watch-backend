<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_generated_websites', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('conversation_id')
                ->constrained('ai_message_conversations')
                ->onDelete('cascade');
            $table->foreignId('message_id')
                ->constrained('ai_app_messages')
                ->onDelete('cascade');
            $table->string('name');
            $table->json('files');
            $table->string('entry_file')->default('index.html');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generated_websites');
    }
};
