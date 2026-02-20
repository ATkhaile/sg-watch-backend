<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_message_id')->constrained('contact_messages')->cascadeOnDelete();
            $table->string('file_path', 500)->comment('ファイルパス');
            $table->string('file_name', 255)->comment('元のファイル名');
            $table->unsignedBigInteger('file_size')->comment('ファイルサイズ（バイト）');
            $table->string('mime_type', 100)->comment('MIMEタイプ');
            $table->timestamps();

            $table->index('contact_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_message_attachments');
    }
};
