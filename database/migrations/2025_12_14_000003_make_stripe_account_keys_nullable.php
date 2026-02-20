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
        Schema::table('stripe_account', function (Blueprint $table) {
            $table->string('public_key')->nullable()->change();
            $table->string('secret_key')->nullable()->change();
            $table->boolean('is_test_mode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_account', function (Blueprint $table) {
            $table->string('public_key')->nullable(false)->change();
            $table->string('secret_key')->nullable(false)->change();
            $table->boolean('is_test_mode')->nullable(false)->change();
        });
    }
};
