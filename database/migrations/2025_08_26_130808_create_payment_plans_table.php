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
        Schema::create('tdbs_payment_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->index('payment_plans_account_id_foreign');
            $table->unsignedBigInteger('plan_id')->index('payment_plans_plan_id_foreign');
            $table->unsignedBigInteger('card_id')->nullable()->index('payment_plans_card_id_foreign');
            $table->string('payment_intent_id')->nullable();
            $table->bigInteger('total_amount')->comment('総額');
            $table->tinyInteger('payment_status')->default(1)->comment('支払いステータス');
            $table->unsignedBigInteger('coupon_id')->nullable()->index('payment_plans_coupon_id_foreign');
            $table->bigInteger('discount_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraints
            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('card_id')->references('id')->on('tdbs_cards')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('coupon_id')->references('id')->on('tdbs_coupons')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('plan_id')->references('id')->on('tdbs_plans')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_payment_plans');
    }
};
