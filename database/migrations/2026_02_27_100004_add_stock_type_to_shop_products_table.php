<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->string('stock_type')->default('in_stock')->after('stock_quantity')->comment('Loại hàng: in_stock (có sẵn), pre_order (đặt trước)');
            $table->index('stock_type');
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropIndex(['stock_type']);
            $table->dropColumn('stock_type');
        });
    }
};
