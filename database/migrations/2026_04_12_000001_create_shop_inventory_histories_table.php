<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('type', 20); // import | export
            $table->unsignedInteger('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->string('reference_type', 50)->nullable(); // admin_update | order
            $table->unsignedBigInteger('reference_id')->nullable(); // order_id for exports
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
            $table->index(['product_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_inventory_histories');
    }
};
