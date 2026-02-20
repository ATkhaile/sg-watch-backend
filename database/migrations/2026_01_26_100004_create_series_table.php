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
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('シリーズタイトル');
            $table->text('description')->nullable()->comment('シリーズ説明');
            $table->string('thumbnail')->nullable()->comment('サムネイル画像パス');
            $table->boolean('is_member_only')->default(false)->comment('会員限定フラグ');
            $table->boolean('is_active')->default(true)->comment('公開状態');
            $table->integer('display_order')->default(0)->comment('表示順');
            $table->string('creator')->nullable()->comment('作成者');
            $table->timestamps();
            $table->softDeletes();

            // インデックス
            $table->index('is_active');
            $table->index('is_member_only');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
