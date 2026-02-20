<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->comment('Mã đơn hàng');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('pending')->comment('Trạng thái đơn hàng');
            $table->string('payment_status')->default('pending')->comment('Trạng thái thanh toán');
            $table->string('payment_method')->nullable()->comment('cod, bank_transfer, credit_card, stripe');

            // Tiền
            $table->decimal('subtotal', 15, 0)->default(0);
            $table->decimal('shipping_fee', 15, 0)->default(0);
            $table->decimal('discount_amount', 15, 0)->default(0);
            $table->decimal('total_amount', 15, 0)->default(0);
            $table->string('currency', 3)->default('VND');
            $table->string('coupon_code')->nullable();

            // Thông tin giao hàng
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_email')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_city')->nullable();
            $table->string('shipping_country')->default('VN');
            $table->string('shipping_postal_code')->nullable();

            // Ghi chú
            $table->text('note')->nullable();
            $table->text('admin_note')->nullable();

            // Tracking
            $table->string('tracking_number')->nullable();
            $table->string('shipping_carrier')->nullable();

            // Timestamps trạng thái
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('payment_status');
        });

        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name')->comment('Snapshot tên SP');
            $table->string('product_sku')->comment('Snapshot mã SP');
            $table->string('product_image')->nullable()->comment('Snapshot ảnh SP');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 0);
            $table->decimal('total_price', 15, 0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('shop_orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('shop_products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
        Schema::dropIfExists('shop_orders');
    }
};
