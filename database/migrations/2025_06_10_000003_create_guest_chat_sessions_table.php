<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guest_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name');
            $table->string('code', 8)->unique();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            $table->index('email');
        });

        // Add foreign key constraint to existing session_id column
        Schema::table('guest_chat_messages', function (Blueprint $table) {
            $table->foreign('session_id')->references('id')->on('guest_chat_sessions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('guest_chat_messages', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
        });
        Schema::dropIfExists('guest_chat_sessions');
    }
};
