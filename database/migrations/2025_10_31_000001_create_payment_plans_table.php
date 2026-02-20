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
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->ulid('payment_plan_id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('payment_plan_icon')->default('crown')->comment('プランのアイコン');
            $table->integer('price')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('stripe_account_id');
            $table->tinyInteger('payment_plan_type')->comment('法人or個人');
            $table->string('stripe_plan_id')->nullable()->comment('StripeのプランID');
            $table->string('stripe_price_id')->nullable()->comment('Price ID from Stripe (price_...)');
            $table->string('stripe_payment_link')->nullable()->comment('StripeのプランID');
            $table->double('cancel_hours')->default(120)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('stripe_account_id')->references('stripe_account_id')->on('stripe_account')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};
