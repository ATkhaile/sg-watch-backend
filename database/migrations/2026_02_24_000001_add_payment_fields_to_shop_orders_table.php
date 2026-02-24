<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->decimal('cod_fee', 15, 0)->default(0)->after('shipping_fee')->comment('Phí COD (代引き手数料)');
            $table->decimal('deposit_amount', 15, 0)->default(0)->after('cod_fee')->comment('Tiền đặt cọc');
            $table->string('payment_receipt')->nullable()->after('payment_method')->comment('Ảnh biên lai chuyển khoản');
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn(['cod_fee', 'deposit_amount', 'payment_receipt']);
        });
    }
};
