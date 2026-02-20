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
        Schema::create('stripe_account', function (Blueprint $table) {
            $table->ulid('stripe_account_id')->primary();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->text('stripe_key')->comment('StripeのプランID');
            $table->text('stripe_secret')->comment('StripeのプランID');
            $table->text('stripe_payment_link')->nullable()->comment('StripeのプランID');
            $table->string('stripe_webhook_secret')->comment('StripeのWebhookシークレット');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_account');
    }
};
