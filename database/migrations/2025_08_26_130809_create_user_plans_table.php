<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tdbs_user_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->default(1)->index('user_plans_shop_id_foreign');
            $table->unsignedBigInteger('account_id')->index('user_plans_account_id_foreign');
            $table->unsignedBigInteger('plan_id')->index('user_plans_plan_id_foreign');
            $table->string('subscription_id')->nullable();
            $table->unsignedBigInteger('next_month_plan_id')->nullable()->index('user_plans_next_month_plan_id_foreign');
            $table->unsignedBigInteger('next_payment_card_id')->nullable()->index('user_plans_next_payment_card_id_foreign');
            $table->unsignedBigInteger('payment_plan_id')->nullable()->index('user_plans_payment_plan_id_foreign');
            $table->string('charge_id')->nullable();
            $table->string('payment_number')->nullable();
            $table->dateTime('payment_at');
            $table->dateTime('expire_end');
            $table->string('client_secret')->nullable()->unique();
            $table->integer('count_remaining');
            $table->string('payment_status')->default('1');
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraints
            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('next_month_plan_id')->references('id')->on('tdbs_plans')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('next_payment_card_id')->references('id')->on('tdbs_cards')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('payment_plan_id')->references('id')->on('tdbs_payment_plans')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('plan_id')->references('id')->on('tdbs_plans')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_user_plans');
    }
};
