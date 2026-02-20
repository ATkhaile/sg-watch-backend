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
        // Rename column in ai_app_messages table
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->renameColumn('appp_id', 'app_id');
        });

        // Rename column in ai_message_conversations table
        Schema::table('ai_message_conversations', function (Blueprint $table) {
            $table->renameColumn('appp_id', 'app_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert column name in ai_app_messages table
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->renameColumn('app_id', 'appp_id');
        });

        // Revert column name in ai_message_conversations table
        Schema::table('ai_message_conversations', function (Blueprint $table) {
            $table->renameColumn('app_id', 'appp_id');
        });
    }
};
