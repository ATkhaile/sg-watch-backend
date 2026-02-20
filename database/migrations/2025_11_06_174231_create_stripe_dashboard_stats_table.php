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
        Schema::create('stripe_dashboard_stats', function (Blueprint $table) {
            $table->id();
            $table->ulid('stripe_account_id');
            $table->integer('payment_links_count')->default(0)->comment('ペイメントリンク');
            $table->integer('prices_count')->default(0)->comment('価格設定');
            $table->integer('products_count')->default(0)->comment('商品');
            $table->integer('customers_count')->default(0)->comment('顧客');
            $table->integer('subscriptions_count')->default(0)->comment('サブスクリプション');
            $table->integer('invoices_count')->default(0)->comment('請求書');
            $table->integer('charges_count')->default(0)->comment('支払い');
            $table->integer('payment_intents_count')->default(0)->comment('ペイメントインテント');
            $table->integer('refunds_count')->default(0)->comment('返金');
            $table->integer('events_count')->default(0)->comment('イベントログ');
            $table->integer('balance_transactions_count')->default(0)->comment('残高取引');
            $table->integer('checkout_sessions_count')->default(0)->comment('チェックアウトセッション');
            $table->integer('invoice_items_count')->default(0)->comment('請求書アイテム');
            $table->integer('payouts_count')->default(0)->comment('支払い出金');
            $table->integer('payment_link_line_items_count')->default(0)->comment('ペイメントリンク明細');
            $table->integer('subscription_items_count')->default(0)->comment('サブスクリプションアイテム');
            $table->timestamp('last_synced_at')->nullable()->comment('最終同期日時');
            $table->timestamps();

            $table->unique('stripe_account_id');
            $table->foreign('stripe_account_id')->references('stripe_account_id')->on('stripe_account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_dashboard_stats');
    }
};
