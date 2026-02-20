<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guest_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->string('guest_email');
            $table->string('guest_name');
            $table->text('message');
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_from_admin')->default(false);
            $table->unsignedBigInteger('admin_user_id')->nullable();
            // $table->unsignedBigInteger('session_id')->nullable()->after('id');
            $table->timestamps();

            $table->index('guest_email');
            $table->foreign('admin_user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('session_id')->references('id')->on('guest_chat_sessions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_chat_messages');
    }
};
