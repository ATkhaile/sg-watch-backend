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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stripe_customers');
    }
};
