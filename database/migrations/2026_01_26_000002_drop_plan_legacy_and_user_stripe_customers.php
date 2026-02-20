<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * PlanLegacy (旧payment_plans) と UserStripeCustomer を完全削除
     * 理由: UserStripeCustomer作成処理が実装されておらず未使用
     * Planで全ての機能をカバーできるため統合不要
     */
    public function up(): void
    {
        // 1. user_stripe_customers テーブルを削除（FK制約あり）
        Schema::dropIfExists('user_stripe_customers');

        // 2. payment_plans テーブルを削除（PlanLegacy用の旧テーブル）
        Schema::dropIfExists('payment_plans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // payment_plans テーブルを復元（元のテーブル名で）
        if (!Schema::hasTable('payment_plans')) {
            Schema::create('payment_plans', function (Blueprint $table) {
                $table->ulid('payment_plan_id')->primary();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('payment_plan_icon')->default('crown')->comment('プランのアイコン');
                $table->integer('price')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->string('stripe_account_id')->nullable();
                $table->tinyInteger('payment_plan_type')->comment('法人or個人');
                $table->string('stripe_plan_id')->nullable()->comment('StripeのプランID');
                $table->string('stripe_price_id')->nullable()->comment('Price ID from Stripe (price_...)');
                $table->string('stripe_payment_link')->nullable()->comment('StripeのプランID');
                $table->double('cancel_hours')->default(120)->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                // stripe_account外部キーはテーブルが存在する場合のみ追加
            });

            // stripe_accountテーブルが存在する場合のみ外部キーを追加
            if (Schema::hasTable('stripe_account')) {
                Schema::table('payment_plans', function (Blueprint $table) {
                    $table->foreign('stripe_account_id')->references('stripe_account_id')->on('stripe_account')->onDelete('cascade');
                });
            }
        }

        // user_stripe_customers テーブルを復元
        if (!Schema::hasTable('user_stripe_customers')) {
            Schema::create('user_stripe_customers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('payment_plan_id');
                $table->string('stripe_customer_id');
                $table->string('stripe_price_id')->nullable()->comment('Price ID from Stripe (price_...) - nullable for payment links with multiple prices');
                $table->string('stripe_account_identifier')->comment('Stripe account unique');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('payment_plan_id')->references('payment_plan_id')->on('payment_plans')->onDelete('cascade');

                $table->unique(['user_id', 'payment_plan_id', 'stripe_account_identifier'], 'user_stripe_unique');
            });
        }
    }
};
