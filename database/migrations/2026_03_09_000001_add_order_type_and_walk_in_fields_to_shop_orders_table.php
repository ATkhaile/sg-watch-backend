<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            // Loại đơn hàng: online (mặc định) hoặc walk_in (khách đến cửa hàng)
            $table->string('order_type', 20)->default('online')->after('order_number')
                ->comment('online: đơn hàng online, walk_in: khách mua tại cửa hàng');

            // Tên khách hàng walk-in (khi không có tài khoản trên hệ thống)
            $table->string('customer_name', 255)->nullable()->after('order_type')
                ->comment('Tên khách hàng walk-in (không có tài khoản)');

            // Cho phép user_id nullable (walk-in không cần user)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Cho phép shipping_name, shipping_phone, shipping_address nullable (walk-in không cần giao hàng)
            $table->string('shipping_name')->nullable()->change();
            $table->string('shipping_phone')->nullable()->change();
            $table->text('shipping_address')->nullable()->change();

            $table->index('order_type');
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropIndex(['order_type']);
            $table->dropColumn(['order_type', 'customer_name']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
