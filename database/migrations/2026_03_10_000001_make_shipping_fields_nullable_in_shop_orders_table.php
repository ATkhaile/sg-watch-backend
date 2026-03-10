<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('shipping_name')->nullable()->change();
            $table->string('shipping_phone')->nullable()->change();
            $table->text('shipping_address')->nullable()->change();
            $table->string('shipping_country')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('shipping_name')->nullable(false)->change();
            $table->string('shipping_phone')->nullable(false)->change();
            $table->text('shipping_address')->nullable(false)->change();
            $table->string('shipping_country')->nullable(false)->default('VN')->change();
        });
    }
};
