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
        // 1. Rename tdbs_shops table to locations
        Schema::rename('tdbs_shops', 'locations'); 

        // 2. Rename shop_type to location_type in locations table
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('shop_type', 'location_type');
        });

        // 3. Rename shop_id to location_id in related tables
        $tables = [
            'coupons',
            'contract_documents',
            'slot_options',
            'availability_slots',
            'tdbs_plans',
            'tdbs_user_plans',
            'tdbs_reservations',
            'tdbs_payment_histories',
            'tdbs_booking_tmps',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'shop_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('shop_id', 'location_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Rename location_id back to shop_id in related tables
        $tables = [
            'coupons',
            'contract_documents',
            'slot_options',
            'availability_slots',
            'tdbs_plans',
            'tdbs_user_plans',
            'tdbs_reservations',
            'tdbs_payment_histories',
            'tdbs_booking_tmps',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'location_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->renameColumn('location_id', 'shop_id');
                });
            }
        }

        // 2. Rename location_type back to shop_type in locations table
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('location_type', 'shop_type');
        });

        // 3. Rename locations table back to tdbs_shops
        Schema::rename('locations', 'tdbs_shops');
    }
};
