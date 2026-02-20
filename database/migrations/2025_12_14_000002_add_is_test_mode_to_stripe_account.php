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
        Schema::table('stripe_account', function (Blueprint $table) {
            $table->boolean('is_test_mode')->default(false)->after('business_type')->comment('テストモードかどうか');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_account', function (Blueprint $table) {
            $table->dropColumn('is_test_mode');
        });
    }
};
