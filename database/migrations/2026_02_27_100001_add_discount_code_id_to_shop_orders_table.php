<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->foreignId('discount_code_id')->nullable()->after('coupon_code')->constrained('discount_codes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropForeign(['discount_code_id']);
            $table->dropColumn('discount_code_id');
        });
    }
};
