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
        Schema::create('product_option_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->string('label', 100)->comment('ラベル名（例：開催日時、サイズ）');
            $table->boolean('is_required')->default(false)->comment('必須選択かどうか');
            $table->boolean('is_multiple')->default(false)->comment('複数選択可能かどうか');
            $table->integer('display_order')->default(0)->comment('表示順');
            $table->boolean('status')->default(true)->comment('有効/無効');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_groups');
    }
};
