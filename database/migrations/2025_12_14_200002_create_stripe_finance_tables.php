<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 財務・残高テーブル (6テーブル)
     */
    public function up(): void
    {
        // 1. stripe_balances - 残高
        Schema::create('stripe_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->nullable()->comment('Stripe Balance ID');
            $table->string('currency', 10)->comment('通貨');
            $table->bigInteger('available')->default(0)->comment('利用可能残高');
            $table->bigInteger('pending')->default(0)->comment('保留中残高');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->string('status', 50)->nullable()->comment('ステータス');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'currency']);
        });

        // 2. stripe_balance_transactions - 残高取引
        Schema::create('stripe_balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Balance Transaction ID (txn_xxx)');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->string('type', 50)->comment('タイプ');
            $table->bigInteger('net')->comment('純額');
            $table->bigInteger('fee')->default(0)->comment('手数料');
            $table->string('source_id', 100)->nullable()->comment('ソースID');
            $table->text('description')->nullable()->comment('説明');
            $table->string('status', 50)->comment('ステータス');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'type'], 'bal_txn_acct_type_idx');
            $table->index(['stripe_account_id', 'stripe_created'], 'bal_txn_acct_created_idx');
        });

        // 3. stripe_payouts - 振込
        Schema::create('stripe_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Payout ID (po_xxx)');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->timestamp('arrival_date')->nullable()->comment('到着予定日');
            $table->string('status', 50)->comment('ステータス');
            $table->string('method', 50)->nullable()->comment('方法');
            $table->string('type', 50)->nullable()->comment('タイプ');
            $table->string('failure_code', 100)->nullable()->comment('失敗コード');
            $table->text('failure_message')->nullable()->comment('失敗メッセージ');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id']);
            $table->index(['stripe_account_id', 'status']);
        });

        // 4. stripe_disputes - 異議申し立て
        Schema::create('stripe_disputes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Dispute ID (dp_xxx)');
            $table->string('charge_id', 100)->comment('請求ID');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->string('status', 50)->comment('ステータス');
            $table->json('evidence_details')->nullable()->comment('証拠詳細');
            $table->timestamp('evidence_due_by')->nullable()->comment('証拠提出期限');
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
            $table->index(['stripe_account_id', 'status']);
        });

        // 5. stripe_credit_notes - クレジットノート
        Schema::create('stripe_credit_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Credit Note ID (cn_xxx)');
            $table->string('invoice_id', 100)->comment('請求書ID');
            $table->string('customer_id', 100)->comment('顧客ID');
            $table->bigInteger('credit_amount')->comment('クレジット金額');
            $table->string('currency', 10)->comment('通貨');
            $table->string('reason', 100)->nullable()->comment('理由');
            $table->string('status', 50)->comment('ステータス');
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

        // 6. stripe_customer_balance_transactions - 顧客残高取引
        Schema::create('stripe_customer_balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_account_id');
            $table->string('stripe_id', 100)->comment('Stripe Customer Balance Transaction ID (cbtxn_xxx)');
            $table->string('customer_id', 100)->comment('顧客ID');
            $table->string('type', 50)->comment('タイプ');
            $table->bigInteger('amount')->comment('金額');
            $table->string('currency', 10)->comment('通貨');
            $table->bigInteger('ending_balance')->comment('終了残高');
            $table->boolean('livemode')->default(false)->comment('本番モード');
            $table->timestamp('stripe_created')->nullable()->comment('Stripe作成日時');
            $table->text('remarks')->nullable()->comment('備考');
            $table->string('creator')->nullable()->comment('作成者');
            $table->string('updater')->nullable()->comment('更新者');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
            $table->unique(['stripe_account_id', 'stripe_id'], 'cust_bal_txn_acct_stripe_unique');
            $table->index(['stripe_account_id', 'customer_id'], 'cust_bal_txn_acct_cust_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_customer_balance_transactions');
        Schema::dropIfExists('stripe_credit_notes');
        Schema::dropIfExists('stripe_disputes');
        Schema::dropIfExists('stripe_payouts');
        Schema::dropIfExists('stripe_balance_transactions');
        Schema::dropIfExists('stripe_balances');
    }
};
