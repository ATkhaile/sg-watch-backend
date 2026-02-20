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
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique()->comment('曲名');
            $table->string('artist')->nullable()->comment('アーティスト名');
            $table->string('genre', 100)->nullable()->comment('ジャンル');
            $table->text('audio_url')->comment('音楽ファイルURL');
            $table->text('cover_image_url')->comment('カバー画像URL');
            $table->integer('duration')->default(0)->comment('再生時間（秒）');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music');
    }
};
