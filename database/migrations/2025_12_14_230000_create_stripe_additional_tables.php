<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 不足している10テーブルを追加
     */
    public function up(): void
    {
        // 33. Account Sessions
        if (!Schema::hasTable('stripe_account_sessions')) {
            Schema::create('stripe_account_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Stripe Account Session ID');
                $table->string('account_id')->nullable()->comment('Connected Account ID');
                $table->text('client_secret')->nullable();
                $table->json('components')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'acct_sess_acct_stripe_idx');
            });
        }

        // 38. Financial Connections Accounts
        if (!Schema::hasTable('stripe_financial_connections_accounts')) {
            Schema::create('stripe_financial_connections_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Financial Connection Account ID');
                $table->string('institution_name')->nullable();
                $table->string('category')->nullable();
                $table->string('subcategory')->nullable();
                $table->string('status')->nullable();
                $table->json('balance')->nullable();
                $table->string('currency')->nullable();
                $table->string('last4')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'fin_conn_acct_stripe_idx');
            });
        }

        // 39. Financial Connections Sessions
        if (!Schema::hasTable('stripe_financial_connections_sessions')) {
            Schema::create('stripe_financial_connections_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Financial Connection Session ID');
                $table->json('account_holder')->nullable();
                $table->json('accounts')->nullable();
                $table->json('filters')->nullable();
                $table->json('permissions')->nullable();
                $table->string('client_secret')->nullable();
                $table->string('return_url')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'fin_conn_sess_acct_stripe_idx');
            });
        }

        // 40. Treasury Financial Accounts
        if (!Schema::hasTable('stripe_treasury_financial_accounts')) {
            Schema::create('stripe_treasury_financial_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Treasury Financial Account ID');
                $table->json('supported_currencies')->nullable();
                $table->json('balance')->nullable();
                $table->string('country')->nullable();
                $table->string('status')->nullable();
                $table->json('status_details')->nullable();
                $table->json('active_features')->nullable();
                $table->json('pending_features')->nullable();
                $table->json('restricted_features')->nullable();
                $table->json('financial_addresses')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'treas_fin_acct_stripe_idx');
            });
        }

        // 41. Treasury Transactions
        if (!Schema::hasTable('stripe_treasury_transactions')) {
            Schema::create('stripe_treasury_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Treasury Transaction ID');
                $table->string('financial_account_id')->nullable();
                $table->bigInteger('amount')->default(0);
                $table->string('currency', 3)->nullable();
                $table->string('type')->nullable();
                $table->string('status')->nullable();
                $table->text('description')->nullable();
                $table->json('balance_impact')->nullable();
                $table->json('flow_details')->nullable();
                $table->string('flow_type')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'financial_account_id'], 'treas_tx_acct_fin_idx');
            });
        }

        // 50. Verification Reports
        if (!Schema::hasTable('stripe_verification_reports')) {
            Schema::create('stripe_verification_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Verification Report ID');
                $table->string('verification_session_id')->nullable();
                $table->string('type')->nullable();
                $table->json('document')->nullable();
                $table->json('id_number')->nullable();
                $table->json('selfie')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'verification_session_id'], 'verif_rpt_acct_sess_idx');
            });
        }

        // 53. Terminal Connection Tokens
        if (!Schema::hasTable('stripe_terminal_connection_tokens')) {
            Schema::create('stripe_terminal_connection_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->nullable()->comment('Connection Token (no ID from Stripe)');
                $table->string('location_id')->nullable();
                $table->text('secret')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'location_id'], 'term_conn_tok_acct_loc_idx');
            });
        }

        // 54. Sigma Scheduled Query Runs
        if (!Schema::hasTable('stripe_sigma_scheduled_query_runs')) {
            Schema::create('stripe_sigma_scheduled_query_runs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Sigma Scheduled Query Run ID');
                $table->string('title')->nullable();
                $table->text('sql')->nullable();
                $table->string('status')->nullable();
                $table->string('result_available_until')->nullable();
                $table->string('file_id')->nullable();
                $table->string('error')->nullable();
                $table->timestamp('data_load_time')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'sigma_qry_acct_stripe_idx');
            });
        }

        // 55. Reporting Report Runs
        if (!Schema::hasTable('stripe_reporting_report_runs')) {
            Schema::create('stripe_reporting_report_runs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Reporting Report Run ID');
                $table->string('report_type')->nullable();
                $table->string('status')->nullable();
                $table->string('result_id')->nullable();
                $table->json('parameters')->nullable();
                $table->timestamp('succeeded_at')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'rpt_run_acct_stripe_idx');
            });
        }

        // 56. Payment Records
        if (!Schema::hasTable('stripe_payment_records')) {
            Schema::create('stripe_payment_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stripe_account_id');
                $table->string('stripe_id')->comment('Payment Record ID');
                $table->string('charge_id')->nullable();
                $table->string('payment_intent_id')->nullable();
                $table->string('invoice_id')->nullable();
                $table->bigInteger('amount')->default(0);
                $table->bigInteger('amount_canceled')->default(0);
                $table->bigInteger('amount_failed')->default(0);
                $table->bigInteger('amount_guaranteed')->default(0);
                $table->bigInteger('amount_requested')->default(0);
                $table->string('currency', 3)->nullable();
                $table->string('customer_id')->nullable();
                $table->json('customer_details')->nullable();
                $table->string('payment_method_id')->nullable();
                $table->json('payment_method_details')->nullable();
                $table->string('payment_reference')->nullable();
                $table->json('shipping_details')->nullable();
                $table->string('status')->nullable();
                $table->boolean('livemode')->default(false);
                $table->timestamp('stripe_created')->nullable();
                $table->text('remarks')->nullable();
                $table->string('creator')->nullable();
                $table->string('updater')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stripe_account_id')->references('id')->on('stripe_accounts')->onDelete('cascade');
                $table->index(['stripe_account_id', 'stripe_id'], 'pmt_rec_acct_stripe_idx');
                $table->index(['stripe_account_id', 'customer_id'], 'pmt_rec_acct_cust_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_payment_records');
        Schema::dropIfExists('stripe_reporting_report_runs');
        Schema::dropIfExists('stripe_sigma_scheduled_query_runs');
        Schema::dropIfExists('stripe_terminal_connection_tokens');
        Schema::dropIfExists('stripe_verification_reports');
        Schema::dropIfExists('stripe_treasury_transactions');
        Schema::dropIfExists('stripe_treasury_financial_accounts');
        Schema::dropIfExists('stripe_financial_connections_sessions');
        Schema::dropIfExists('stripe_financial_connections_accounts');
        Schema::dropIfExists('stripe_account_sessions');
    }
};
