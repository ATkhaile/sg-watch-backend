<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cart items: drop cart_id FK (which uses the unique index), then drop unique, add color column
        Schema::table('shop_cart_items', function (Blueprint $table) {
            $table->dropForeign('shop_cart_items_cart_id_foreign');
            $table->dropUnique('shop_cart_items_cart_id_product_id_unique');

            $table->unsignedBigInteger('product_color_id')->nullable()->after('product_id');

            $table->foreign('cart_id')->references('id')->on('shop_carts')->cascadeOnDelete();
            $table->foreign('product_color_id')->references('id')->on('shop_product_colors')->onDelete('cascade');

            $table->index(['cart_id', 'product_id', 'product_color_id']);
        });

        // Order items: add product_color_id
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_color_id')->nullable()->after('product_id');
            $table->string('product_color_name')->nullable()->after('product_color_id')->comment('Snapshot tên màu SP');
            $table->foreign('product_color_id')->references('id')->on('shop_product_colors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('shop_cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_color_id']);
            $table->dropIndex(['cart_id', 'product_id', 'product_color_id']);
            $table->dropColumn('product_color_id');
            $table->unique(['cart_id', 'product_id']);
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->dropForeign(['product_color_id']);
            $table->dropColumn(['product_color_id', 'product_color_name']);
        });
    }
};
