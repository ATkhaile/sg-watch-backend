<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_stripe_customer_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('stripe_customer_id')->comment('StripeのCustomer ID (cus_...)');
            $table->string('stripe_account_id')->nullable()->comment('Stripe Connectアカウント識別子');
            $table->string('label')->nullable()->comment('識別用ラベル（例: メインアカウント、サブアカウント等）');
            $table->boolean('is_primary')->default(false)->comment('プライマリ顧客IDかどうか');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'stripe_customer_id', 'stripe_account_id'], 'user_stripe_customer_link_unique');
            $table->index('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_stripe_customer_links');
    }
};
