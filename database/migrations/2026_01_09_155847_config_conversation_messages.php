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
        Schema::table('ai_message_conversations', function (Blueprint $table) {
            $table->text('overview')->nullable();
            $table->text('summary')->nullable();
            $table->unsignedInteger('summary_messages_count')->default(0);
        });
        Schema::table('ai_applications', function (Blueprint $table) {
            $table->unsignedInteger('loop_count')->default(5);
            $table->string('context_type')->default('conversation');
        });
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->string('context_type')->default('conversation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
