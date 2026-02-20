<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stripe請求書/レシートURLを保存するカラムを追加
     */
    public function up(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->string('invoice_url', 512)->nullable()->after('stripe_payment_link_id')
                ->comment('Stripe請求書またはレシートのURL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropColumn('invoice_url');
        });
    }
};
