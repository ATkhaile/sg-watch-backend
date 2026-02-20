<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('タイトル');
            $table->unsignedBigInteger('category_id')->comment('カテゴリーのテープルにid')->nullable();
            $table->text('content')->comment('内容');
            $table->unsignedTinyInteger('status')->nullable()->comment('ステータス');
            $table->unsignedBigInteger('creator')->comment('作成者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
