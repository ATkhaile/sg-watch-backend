<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stripe_dashboard_stats', function (Blueprint $table) {
            $table->unsignedInteger('disputes_count')->default(0)->after('payouts_count');
            $table->unsignedInteger('credit_notes_count')->default(0)->after('disputes_count');
            $table->unsignedInteger('payment_methods_count')->default(0)->after('credit_notes_count');
        });
    }

    public function down(): void
    {
        Schema::table('stripe_dashboard_stats', function (Blueprint $table) {
            $table->dropColumn(['disputes_count', 'credit_notes_count', 'payment_methods_count']);
        });
    }
};
