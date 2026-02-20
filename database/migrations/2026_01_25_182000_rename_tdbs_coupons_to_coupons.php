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
            $table->dropForeign(['coupon_id']);
        });

        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
        });

        // Rename the table
        Schema::rename('tdbs_coupons', 'coupons');

        // Restore foreign key constraints with new table name
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('coupons')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('coupons')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
        });

        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
        });

        // Rename back
        Schema::rename('coupons', 'tdbs_coupons');

        // Restore original foreign key constraints
        Schema::table('tdbs_payment_plans', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('tdbs_coupons')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('tdbs_coupons')->onUpdate('restrict')->onDelete('restrict');
        });
    }
};
