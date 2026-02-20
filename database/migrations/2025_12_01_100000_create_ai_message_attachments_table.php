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
        Schema::create('ai_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_message_id')->constrained('ai_app_messages')->onDelete('cascade');
            $table->string('original_name');           // Tên file gốc
            $table->string('file_path');               // Đường dẫn lưu trữ (UUID filename)
            $table->string('mime_type');               // image/jpeg, application/pdf, etc.
            $table->unsignedBigInteger('file_size');   // Size in bytes
            $table->timestamps();

            $table->index('ai_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_message_attachments');
    }
};
