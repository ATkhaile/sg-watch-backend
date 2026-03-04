<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->boolean('is_domestic')->default(true)->after('is_featured');
            $table->integer('sale_percent')->nullable()->after('is_domestic');
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn(['is_domestic', 'sale_percent']);
        });
    }
};
