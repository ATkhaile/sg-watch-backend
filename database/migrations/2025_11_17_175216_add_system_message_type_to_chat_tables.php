<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update chat_messages table to support 'system' message type
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN message_type ENUM('text', 'file', 'system') DEFAULT 'text'");

        // Update chat_group_messages table to support 'system' message type
        DB::statement("ALTER TABLE chat_group_messages MODIFY COLUMN message_type ENUM('text', 'file', 'system') DEFAULT 'text'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN message_type ENUM('text', 'file') DEFAULT 'text'");
        DB::statement("ALTER TABLE chat_group_messages MODIFY COLUMN message_type ENUM('text', 'file') DEFAULT 'text'");
    }
};
