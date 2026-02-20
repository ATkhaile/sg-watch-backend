<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 不足しているStripeテーブルを追加
     */
    public function up(): void
    {
        // stripe_setup_intents - セットアップインテント
        if (!Schema::hasTable('stripe_setup_intents')) {
            Schema::create('stripe_setup_intents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Setup Intent ID (seti_xxx)');
                $table->string('customer_id', 100)->nullable()->comment('顧客ID');
                $table->string('payment_method_id', 100)->nullable()->comment('支払い方法ID');
                $table->string('status', 50)->comment('ステータス');
                $table->string('usage', 50)->nullable()->comment('使用タイプ (off_session, on_session)');
                $table->text('description')->nullable()->comment('説明');
                $table->json('payment_method_types')->nullable()->comment('支払い方法タイプ');
                $table->text('client_secret')->nullable()->comment('クライアントシークレット');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id'], 'setup_intents_acct_stripe_uniq');
                $table->index(['stripe_account_id', 'customer_id'], 'setup_intents_acct_cust_idx');
                $table->index(['stripe_account_id', 'status'], 'setup_intents_acct_status_idx');
            });
        }

        // stripe_quotes - 見積もり
        if (!Schema::hasTable('stripe_quotes')) {
            Schema::create('stripe_quotes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Quote ID (qt_xxx)');
                $table->string('customer_id', 100)->nullable()->comment('顧客ID');
                $table->string('status', 50)->comment('ステータス');
                $table->string('number', 100)->nullable()->comment('見積番号');
                $table->bigInteger('amount_subtotal')->default(0)->comment('小計');
                $table->bigInteger('amount_total')->default(0)->comment('合計');
                $table->string('currency', 10)->nullable()->comment('通貨');
                $table->text('description')->nullable()->comment('説明');
                $table->text('header')->nullable()->comment('ヘッダー');
                $table->text('footer')->nullable()->comment('フッター');
                $table->timestamp('expires_at')->nullable()->comment('有効期限');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id'], 'quotes_acct_stripe_uniq');
                $table->index(['stripe_account_id', 'customer_id'], 'quotes_acct_cust_idx');
                $table->index(['stripe_account_id', 'status'], 'quotes_acct_status_idx');
            });
        }

        // stripe_subscription_schedules - サブスクリプションスケジュール
        if (!Schema::hasTable('stripe_subscription_schedules')) {
            Schema::create('stripe_subscription_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Subscription Schedule ID (sub_sched_xxx)');
                $table->string('customer_id', 100)->nullable()->comment('顧客ID');
                $table->string('subscription_id', 100)->nullable()->comment('サブスクリプションID');
                $table->string('status', 50)->comment('ステータス');
                $table->string('end_behavior', 50)->nullable()->comment('終了時の動作');
                $table->timestamp('current_phase_start')->nullable()->comment('現在フェーズ開始');
                $table->timestamp('current_phase_end')->nullable()->comment('現在フェーズ終了');
                $table->json('phases')->nullable()->comment('フェーズ設定');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id'], 'sub_scheds_acct_stripe_uniq');
                $table->index(['stripe_account_id', 'customer_id'], 'sub_scheds_acct_cust_idx');
                $table->index(['stripe_account_id', 'status'], 'sub_scheds_acct_status_idx');
            });
        }

        // stripe_coupons - クーポン
        if (!Schema::hasTable('stripe_coupons')) {
            Schema::create('stripe_coupons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Coupon ID (coupon_xxx)');
                $table->string('name')->nullable()->comment('クーポン名');
                $table->bigInteger('amount_off')->nullable()->comment('割引額');
                $table->decimal('percent_off', 5, 2)->nullable()->comment('割引率(%)');
                $table->string('duration', 50)->comment('期間タイプ (once, repeating, forever)');
                $table->integer('duration_in_months')->nullable()->comment('適用月数(repeatingの場合)');
                $table->string('currency', 10)->nullable()->comment('通貨');
                $table->timestamp('redeem_by')->nullable()->comment('有効期限');
                $table->integer('max_redemptions')->nullable()->comment('最大利用回数');
                $table->integer('times_redeemed')->default(0)->comment('利用回数');
                $table->boolean('valid')->default(true)->comment('有効フラグ');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id'], 'coupons_acct_stripe_uniq');
                $table->index(['stripe_account_id', 'valid'], 'coupons_acct_valid_idx');
            });
        }

        // stripe_promotion_codes - プロモーションコード
        if (!Schema::hasTable('stripe_promotion_codes')) {
            Schema::create('stripe_promotion_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id', 100)->comment('Stripe Promotion Code ID (promo_xxx)');
                $table->string('coupon_id', 100)->comment('クーポンID');
                $table->string('code', 100)->comment('プロモーションコード');
                $table->boolean('active')->default(true)->comment('有効フラグ');
                $table->string('customer_id', 100)->nullable()->comment('限定顧客ID');
                $table->timestamp('expires_at')->nullable()->comment('有効期限');
                $table->integer('max_redemptions')->nullable()->comment('最大利用回数');
                $table->integer('times_redeemed')->default(0)->comment('利用回数');
                $table->json('restrictions')->nullable()->comment('制限設定');
                $table->boolean('livemode')->default(false)->comment('本番モード');
                $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
                $table->text('remarks')->nullable()->comment('備考');
                $table->string('creator')->nullable()->comment('作成者');
                $table->string('updater')->nullable()->comment('更新者');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->unique(['stripe_account_id', 'stripe_id'], 'promo_codes_acct_stripe_uniq');
                $table->index(['stripe_account_id', 'coupon_id'], 'promo_codes_acct_coupon_idx');
                $table->index(['stripe_account_id', 'code'], 'promo_codes_acct_code_idx');
                $table->index(['stripe_account_id', 'active'], 'promo_codes_acct_active_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_promotion_codes');
        Schema::dropIfExists('stripe_coupons');
        Schema::dropIfExists('stripe_subscription_schedules');
        Schema::dropIfExists('stripe_quotes');
        Schema::dropIfExists('stripe_setup_intents');
    }
};
