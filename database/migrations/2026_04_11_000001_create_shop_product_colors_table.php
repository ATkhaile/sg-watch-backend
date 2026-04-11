<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_product_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('color_code', 50);
            $table->string('color_name', 100);
            $table->string('sku', 100)->nullable();
            $table->decimal('price_jpy', 12, 0)->default(0);
            $table->decimal('price_vnd', 15, 0)->default(0);
            $table->decimal('original_price_jpy', 12, 0)->nullable();
            $table->decimal('original_price_vnd', 15, 0)->nullable();
            $table->decimal('cost_price_jpy', 15, 0)->nullable();
            $table->integer('sale_percent')->nullable();
            $table->integer('points')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('shop_products')->cascadeOnDelete();
            $table->index(['product_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_colors');
    }
};
