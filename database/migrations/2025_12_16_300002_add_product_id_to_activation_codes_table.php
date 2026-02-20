<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            // payment_plan_idをnullableに変更
            $table->string('payment_plan_id')->nullable()->change();

            // product_idカラムを追加（nullable）
            $table->unsignedBigInteger('product_id')->nullable()->after('payment_plan_id');

            // 外部キー制約を追加
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // ユーザーのメールアドレス（client_reference_idがない場合の識別用）
            $table->string('customer_email')->nullable()->after('stripe_session_id');

            // 購入タイプ（plan または product）
            $table->enum('purchase_type', ['plan', 'product'])->default('plan')->after('customer_email');

            // Stripe Payment Link ID
            $table->string('stripe_payment_link_id')->nullable()->after('stripe_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'customer_email', 'purchase_type', 'stripe_payment_link_id']);
        });
    }
};
