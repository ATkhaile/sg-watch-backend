<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Set display_order = id for all existing products
        DB::table('shop_products')->whereNull('deleted_at')->update([
            'display_order' => DB::raw('id'),
        ]);

        Schema::table('shop_products', function (Blueprint $table) {
            $table->unique('display_order');
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropUnique(['display_order']);
        });
    }
};
