<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('error_report_id')->constrained('error_reports')->cascadeOnDelete();
            $table->string('file_path', 500)->comment('ファイルパス');
            $table->string('file_name', 255)->comment('元のファイル名');
            $table->unsignedBigInteger('file_size')->comment('ファイルサイズ（バイト）');
            $table->string('mime_type', 100)->comment('MIMEタイプ');
            $table->timestamps();

            $table->index('error_report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_report_attachments');
    }
};
