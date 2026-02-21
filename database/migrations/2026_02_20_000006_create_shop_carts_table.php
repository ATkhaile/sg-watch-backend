<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('Null nếu là khách vãng lai');
            $table->string('device_id')->nullable()->comment('Device UUID for guest cart (mobile/web)');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index('device_id');
        });

        Schema::create('shop_cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_addition', 15, 0)->comment('Giá tại thời điểm thêm vào giỏ');
            $table->string('currency', 3)->default('VND');
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('shop_carts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('shop_products')->cascadeOnDelete();
            $table->unique(['cart_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_cart_items');
        Schema::dropIfExists('shop_carts');
    }
};
