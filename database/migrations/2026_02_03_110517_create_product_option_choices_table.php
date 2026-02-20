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
        Schema::create('product_option_choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_option_group_id')->comment('オプショングループID');
            $table->string('name', 255)->comment('選択肢名（例：2/5 13:00-13:30）');
            $table->integer('price')->default(0)->comment('追加料金（0なら無料）');
            $table->integer('stock')->nullable()->comment('在庫数（nullなら無制限）');
            $table->integer('display_order')->default(0)->comment('表示順');
            $table->boolean('status')->default(true)->comment('有効/無効');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_option_group_id')->references('id')->on('product_option_groups')->onDelete('cascade');
            $table->index('product_option_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option_choices');
    }
};
