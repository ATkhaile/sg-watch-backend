<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Add generated_files column to store AI-generated images and files
     * Structure: [
     *   {
     *     "type": "image" | "file",
     *     "original_name": "generated_image.png",
     *     "file_path": "ai-chat/generated/uuid.png",
     *     "mime_type": "image/png",
     *     "file_size": 12345,
     *     "file_url": "http://example.com/storage/ai-chat/generated/uuid.png"
     *   }
     * ]
     */
    public function up(): void
    {
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->json('generated_files')->nullable()->after('response_metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->dropColumn('generated_files');
        });
    }
};
