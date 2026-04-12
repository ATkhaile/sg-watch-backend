<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_inventory_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('product_color_id')->nullable()->after('product_id');
            $table->foreign('product_color_id')->references('id')->on('shop_product_colors')->onDelete('set null');
            $table->index('product_color_id');
        });
    }

    public function down(): void
    {
        Schema::table('shop_inventory_histories', function (Blueprint $table) {
            $table->dropForeign(['product_color_id']);
            $table->dropIndex(['product_color_id']);
            $table->dropColumn('product_color_id');
        });
    }
};
