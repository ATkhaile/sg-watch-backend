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
        Schema::create('ai_message_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appp_id')->constrained('ai_applications')->onDelete('cascade');
            $table->text('name')->nullable();
            $table->string('from_source')->nullable();
            $table->foreignId('from_account_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ai_app_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appp_id')->constrained('ai_applications')->onDelete('cascade');
            $table->string('model_provider')->nullable();
            $table->string('model_id')->nullable();
            $table->string('from_source')->nullable();
            $table->foreignId('conversation_id')->constrained('ai_message_conversations')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->text('answer')->nullable();
            $table->json('query')->nullable(); // Store the full query payload sent to the AI provider, excluding the API key
            $table->unsignedBigInteger('message_tokens')->nullable()->default(0);
            $table->unsignedBigInteger('answer_tokens')->nullable()->default(0);
            $table->foreignId('from_account_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status')->default('success'); // success, failed
            $table->text('error_message')->nullable();
            $table->json('response_metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_app_messages');
        Schema::dropIfExists('ai_message_conversations');
    }
};
