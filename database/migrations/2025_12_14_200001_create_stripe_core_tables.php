<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * コア決済機能テーブル (13テーブル)
     */
    public function up(): void
    {
        // 1. stripe_customers - 顧客
        Schema::create('stripe_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Customer ID (cus_xxx)');
            $table->string('name')->nullable()->comment('顧客名');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('phone', 50)->nullable()->comment('電話番号');
            $table->text('description')->nullable()->comment('説明');
            $table->string('invoice_prefix', 50)->nullable()->comment('請求書プレフィックス');
            $table->string('default_payment_method_id', 100)->nullable()->comment('デフォルト支払い方法ID');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'email']);
        });

        // 2. stripe_products - 商品
        Schema::create('stripe_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Product ID (prod_xxx)');
            $table->string('name')->comment('商品名');
            $table->text('description')->nullable()->comment('説明');
            $table->boolean('active')->default(true)->comment('有効フラグ');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'active']);
        });

        // 3. stripe_prices - 価格
        Schema::create('stripe_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Price ID (price_xxx)');
            $table->string('product_id', 100)->nullable()->comment('商品ID');
            $table->bigInteger('unit_amount')->nullable()->comment('単価（最小通貨単位）');
            $table->string('currency', 10)->comment('通貨');
            $table->string('recurring_interval', 20)->nullable()->comment('繰り返し間隔 (day/week/month/year)');
            $table->integer('recurring_interval_count')->nullable()->comment('繰り返し回数');
            $table->string('type', 20)->comment('タイプ (one_time/recurring)');
            $table->boolean('active')->default(true)->comment('有効フラグ');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'product_id']);
        });

        // 4. stripe_payment_intents - 支払いインテント
        Schema::create('stripe_payment_intents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Payment Intent ID (pi_xxx)');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->string('customer_id', 100)->nullable()->comment('顧客ID');
            $table->string('status', 50)->comment('ステータス');
            $table->text('description')->nullable()->comment('説明');
            $table->json('payment_method_types')->nullable()->comment('支払い方法タイプ');
            $table->string('capture_method', 50)->nullable()->comment('キャプチャ方法');
            $table->string('confirmation_method', 50)->nullable()->comment('確認方法');
            $table->string('payment_method_id', 100)->nullable()->comment('支払い方法ID');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'customer_id']);
            $table->index(['stripe_account_id', 'status']);
        });

        // 5. stripe_charges - 請求(Charge)
        Schema::create('stripe_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Charge ID (ch_xxx)');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->string('customer_id', 100)->nullable()->comment('顧客ID');
            $table->string('invoice_id', 100)->nullable()->comment('請求書ID');
            $table->string('payment_intent_id', 100)->nullable()->comment('支払いインテントID');
            $table->text('description')->nullable()->comment('説明');
            $table->string('status', 50)->comment('ステータス');
            $table->boolean('paid')->default(false)->comment('支払済み');
            $table->boolean('refunded')->default(false)->comment('返金済み');
            $table->string('failure_code', 100)->nullable()->comment('失敗コード');
            $table->text('failure_message')->nullable()->comment('失敗メッセージ');
            $table->boolean('captured')->default(false)->comment('キャプチャ済み');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'customer_id']);
            $table->index(['stripe_account_id', 'payment_intent_id']);
        });

        // 6. stripe_subscriptions - サブスクリプション
        Schema::create('stripe_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Subscription ID (sub_xxx)');
            $table->string('customer_id', 100)->comment('顧客ID');
            $table->string('status', 50)->comment('ステータス');
            $table->timestamp('current_period_start')->nullable()->comment('現在の期間開始');
            $table->timestamp('current_period_end')->nullable()->comment('現在の期間終了');
            $table->boolean('cancel_at_period_end')->default(false)->comment('期間終了時にキャンセル');
            $table->timestamp('canceled_at')->nullable()->comment('キャンセル日時');
            $table->timestamp('trial_start')->nullable()->comment('トライアル開始');
            $table->timestamp('trial_end')->nullable()->comment('トライアル終了');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'customer_id']);
            $table->index(['stripe_account_id', 'status']);
        });

        // 7. stripe_subscription_items - サブスクリプションアイテム
        Schema::create('stripe_subscription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Subscription Item ID (si_xxx)');
            $table->string('subscription_id', 100)->comment('サブスクリプションID');
            $table->string('price_id', 100)->comment('価格ID');
            $table->integer('quantity')->default(1)->comment('数量');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'subscription_id'], 'sub_items_acct_sub_idx');
        });

        // 8. stripe_invoices - 請求書
        Schema::create('stripe_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Invoice ID (in_xxx)');
            $table->string('customer_id', 100)->comment('顧客ID');
            $table->string('subscription_id', 100)->nullable()->comment('サブスクリプションID');
            $table->string('status', 50)->comment('ステータス');
            $table->string('number', 100)->nullable()->comment('請求書番号');
            $table->string('billing_reason', 100)->nullable()->comment('請求理由');
            $table->timestamp('due_date')->nullable()->comment('支払期限');
            $table->bigInteger('subtotal')->default(0)->comment('小計');
            $table->bigInteger('tax')->default(0)->comment('税額');
            $table->bigInteger('total')->default(0)->comment('合計');
            $table->string('currency', 10)->comment('通貨');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'customer_id']);
            $table->index(['stripe_account_id', 'subscription_id'], 'invoices_acct_sub_idx');
        });

        // 9. stripe_invoice_items - 請求書アイテム
        Schema::create('stripe_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Invoice Item ID (ii_xxx)');
            $table->string('invoice_id', 100)->nullable()->comment('請求書ID');
            $table->string('customer_id', 100)->comment('顧客ID');
            $table->string('price_id', 100)->nullable()->comment('価格ID');
            $table->integer('quantity')->default(1)->comment('数量');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->text('description')->nullable()->comment('説明');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'invoice_id']);
            $table->index(['stripe_account_id', 'customer_id']);
        });

        // 10. stripe_refunds - 返金
        Schema::create('stripe_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Refund ID (re_xxx)');
            $table->string('charge_id', 100)->nullable()->comment('請求ID');
            $table->string('payment_intent_id', 100)->nullable()->comment('支払いインテントID');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->string('status', 50)->comment('ステータス');
            $table->string('reason', 100)->nullable()->comment('理由');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'charge_id']);
            $table->index(['stripe_account_id', 'payment_intent_id']);
        });

        // 11. stripe_payment_methods - 支払い方法
        Schema::create('stripe_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Payment Method ID (pm_xxx)');
            $table->string('type', 50)->comment('タイプ (card, bank_account, etc.)');
            $table->string('customer_id', 100)->nullable()->comment('顧客ID');
            $table->string('card_brand', 50)->nullable()->comment('カードブランド');
            $table->string('card_last4', 4)->nullable()->comment('カード下4桁');
            $table->integer('exp_month')->nullable()->comment('有効期限月');
            $table->integer('exp_year')->nullable()->comment('有効期限年');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'customer_id']);
        });

        // 12. stripe_payment_links - ペイメントリンク
        Schema::create('stripe_payment_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Payment Link ID (plink_xxx)');
            $table->text('url')->comment('URL');
            $table->boolean('active')->default(true)->comment('有効フラグ');
            $table->string('currency', 10)->nullable()->comment('通貨');
            $table->bigInteger('amount_total')->nullable()->comment('合計金額');
            $table->text('description')->nullable()->comment('説明');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'active']);
        });

        // 13. stripe_checkout_sessions - チェックアウトセッション
        Schema::create('stripe_checkout_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Checkout Session ID (cs_xxx)');
            $table->string('mode', 50)->comment('モード (payment, subscription, setup)');
            $table->string('customer_id', 100)->nullable()->comment('顧客ID');
            $table->string('payment_intent_id', 100)->nullable()->comment('支払いインテントID');
            $table->text('url')->nullable()->comment('URL');
            $table->string('status', 50)->comment('ステータス');
            $table->bigInteger('amount_total')->nullable()->comment('合計金額');
            $table->string('currency', 10)->nullable()->comment('通貨');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'customer_id']);
            $table->index(['stripe_account_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_checkout_sessions');
        Schema::dropIfExists('stripe_payment_links');
        Schema::dropIfExists('stripe_payment_methods');
        Schema::dropIfExists('stripe_refunds');
        Schema::dropIfExists('stripe_invoice_items');
        Schema::dropIfExists('stripe_invoices');
        Schema::dropIfExists('stripe_subscription_items');
        Schema::dropIfExists('stripe_subscriptions');
        Schema::dropIfExists('stripe_charges');
        Schema::dropIfExists('stripe_payment_intents');
        Schema::dropIfExists('stripe_prices');
        Schema::dropIfExists('stripe_products');
        Schema::dropIfExists('stripe_customers');
    }
};
