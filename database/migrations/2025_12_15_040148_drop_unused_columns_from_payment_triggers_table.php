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
        Schema::table('payment_triggers', function (Blueprint $table) {
            $table->dropColumn([
                'has_recurring',
                'has_one_time',
                'pricing_info',
                'total_amount',
                'currency',
                'stripe_last_synced_at',
                'stripe_updated_at',
                'allow_promotion_codes',
                'billing_address_collection',
                'after_completion_type',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_triggers', function (Blueprint $table) {
            $table->boolean('has_recurring')->default(false)->after('stripe_payment_link_url');
            $table->boolean('has_one_time')->default(false)->after('has_recurring');
            $table->json('pricing_info')->nullable()->after('has_one_time');
            $table->integer('total_amount')->nullable()->after('pricing_info');
            $table->string('currency', 10)->default('jpy')->after('total_amount');
            $table->timestamp('stripe_last_synced_at')->nullable()->after('currency');
            $table->timestamp('stripe_updated_at')->nullable()->after('stripe_last_synced_at');
            $table->boolean('allow_promotion_codes')->default(false)->after('stripe_updated_at');
            $table->string('billing_address_collection')->nullable()->after('allow_promotion_codes');
            $table->string('after_completion_type')->nullable()->after('billing_address_collection');
        });
    }
};
