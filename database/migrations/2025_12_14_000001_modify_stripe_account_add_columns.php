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
        Schema::table('stripe_account', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('name', 'display_name');
            $table->renameColumn('stripe_key', 'public_key');
            $table->renameColumn('stripe_secret', 'secret_key');
            $table->renameColumn('stripe_webhook_secret', 'webhook_secret');
            $table->renameColumn('stripe_payment_link', 'payment_link');
        });

        Schema::table('stripe_account', function (Blueprint $table) {
            // Add new columns
            $table->string('status')->default('active')->after('display_name')->comment('アカウント状態: active, inactive');
            $table->string('account_id')->nullable()->after('status')->comment('StripeアカウントID (acct_xxx)');
            $table->string('account_name')->nullable()->after('account_id')->comment('Stripeアカウント表示名');
            $table->string('country', 2)->nullable()->after('account_name')->comment('国コード (JP等)');
            $table->string('currency', 3)->nullable()->after('country')->comment('通貨コード (JPY等)');
            $table->boolean('charges_enabled')->default(false)->after('currency')->comment('決済受付可能');
            $table->boolean('payouts_enabled')->default(false)->after('charges_enabled')->comment('出金可能');
            $table->string('business_type')->nullable()->after('payouts_enabled')->comment('ビジネスタイプ');
            $table->timestamp('last_connected_at')->nullable()->after('payment_link')->comment('最終接続日時');
            $table->timestamp('last_synced_at')->nullable()->after('last_connected_at')->comment('最終同期日時');
            $table->unsignedBigInteger('creator_id')->nullable()->after('last_synced_at')->comment('作成者ID');
            $table->unsignedBigInteger('updator_id')->nullable()->after('creator_id')->comment('更新者ID');

            // Add foreign keys
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updator_id')->references('id')->on('users')->nullOnDelete();
        });

        // Drop old user_id foreign key and column
        Schema::table('stripe_account', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_account', function (Blueprint $table) {
            // Add back user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('display_name');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('stripe_account', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['updator_id']);

            // Drop new columns
            $table->dropColumn([
                'status',
                'account_id',
                'account_name',
                'country',
                'currency',
                'charges_enabled',
                'payouts_enabled',
                'business_type',
                'last_connected_at',
                'last_synced_at',
                'creator_id',
                'updator_id',
            ]);
        });

        Schema::table('stripe_account', function (Blueprint $table) {
            // Rename columns back
            $table->renameColumn('display_name', 'name');
            $table->renameColumn('public_key', 'stripe_key');
            $table->renameColumn('secret_key', 'stripe_secret');
            $table->renameColumn('webhook_secret', 'stripe_webhook_secret');
            $table->renameColumn('payment_link', 'stripe_payment_link');
        });
    }
};
