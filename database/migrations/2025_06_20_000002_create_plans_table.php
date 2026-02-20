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
        Schema::create('plans', function (Blueprint $table) {
            $table->ulid('plan_id')->primary();
            $table->string('name')->comment('プラン名');
            $table->integer('price')->comment('プラン月額金額');
            $table->tinyInteger('plan_type')->comment('法人or個人');
            $table->json('sns_limits')->nullable();
            $table->json('sns_developer')->nullable();
            $table->string('stripe_plan_id')->comment('StripeのプランID');
            $table->text('stripe_key')->comment('StripeのプランID');
            $table->text('stripe_secret')->comment('StripeのプランID');
            $table->text('stripe_payment_link')->comment('StripeのプランID');
            $table->string('stripe_webhook_secret')->comment('StripeのWebhookシークレット');
            $table->double('cancel_hours')->default(120)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
