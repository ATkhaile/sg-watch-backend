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
        // Drop foreign key constraints first
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
        });

        Schema::table('tdbs_user_plans', function (Blueprint $table) {
            $table->dropForeign(['next_payment_card_id']);
        });

        // Rename the table
        Schema::rename('tdbs_cards', 'credit_cards');

        // Restore foreign key constraints with new table name
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->foreign('card_id')->references('id')->on('credit_cards')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->foreign('card_id')->references('id')->on('credit_cards')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_user_plans', function (Blueprint $table) {
            $table->foreign('next_payment_card_id')->references('id')->on('credit_cards')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
        });

        Schema::table('tdbs_user_plans', function (Blueprint $table) {
            $table->dropForeign(['next_payment_card_id']);
        });

        // Rename back
        Schema::rename('credit_cards', 'tdbs_cards');

        // Restore original foreign key constraints
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->foreign('card_id')->references('id')->on('tdbs_cards')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->foreign('card_id')->references('id')->on('tdbs_cards')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_user_plans', function (Blueprint $table) {
            $table->foreign('next_payment_card_id')->references('id')->on('tdbs_cards')->onUpdate('restrict')->onDelete('restrict');
        });
    }
};
