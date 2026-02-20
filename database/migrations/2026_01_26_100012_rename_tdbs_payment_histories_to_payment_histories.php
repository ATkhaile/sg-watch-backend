<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * tdbs_payment_histories → payment_histories へリネーム
     * shop_id → location_id、account_id → user_id へリネーム
     * schedule_id → availability_slot_id へリネーム
     */
    public function up(): void
    {
        $tableName = Schema::hasTable('payment_histories') ? 'payment_histories' : 'tdbs_payment_histories';

        // Get current foreign key constraints
        $foreignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$tableName]))->pluck('CONSTRAINT_NAME');

        // Drop all foreign key constraints
        if ($foreignKeys->isNotEmpty()) {
            Schema::table($tableName, function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $fk) {
                    $table->dropForeign($fk);
                }
            });
        }

        // Rename table
        if (Schema::hasTable('tdbs_payment_histories') && !Schema::hasTable('payment_histories')) {
            Schema::rename('tdbs_payment_histories', 'payment_histories');
        }

        // Rename columns using raw SQL (avoid Doctrine DBAL issues) - check if column exists first
        if (Schema::hasColumn('payment_histories', 'shop_id')) {
            DB::statement('ALTER TABLE payment_histories CHANGE shop_id location_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('payment_histories', 'account_id')) {
            DB::statement('ALTER TABLE payment_histories CHANGE account_id user_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('payment_histories', 'schedule_id')) {
            DB::statement('ALTER TABLE payment_histories CHANGE schedule_id availability_slot_id BIGINT UNSIGNED NULL');
        }
        if (Schema::hasColumn('payment_histories', 'billing_prefecture_id')) {
            DB::statement('ALTER TABLE payment_histories CHANGE billing_prefecture_id prefecture_id BIGINT UNSIGNED NULL');
        }

        // Add new foreign key constraints (check if they don't already exist)
        $existingFks = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'payment_histories'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        Schema::table('payment_histories', function (Blueprint $table) use ($existingFks) {
            if (!$existingFks->contains('payment_histories_location_id_foreign')) {
                $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            }
            if (!$existingFks->contains('payment_histories_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!$existingFks->contains('payment_histories_availability_slot_id_foreign')) {
                $table->foreign('availability_slot_id')->references('id')->on('availability_slots')->onDelete('set null');
            }
            // Note: prefecture_id foreign key is NOT added because prefectures.prefecture_id is CHAR(26) ULID
            // but billing_prefecture_id was BIGINT UNSIGNED - type mismatch
        });

        // Add reservation_id foreign key if reservations table exists
        if (Schema::hasTable('reservations') && Schema::hasColumn('payment_histories', 'reservation_id')) {
            if (!$existingFks->contains('payment_histories_reservation_id_foreign')) {
                Schema::table('payment_histories', function (Blueprint $table) {
                    $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
                });
            }
        }

        // Add card_id foreign key if cards table exists
        if (Schema::hasTable('cards') && Schema::hasColumn('payment_histories', 'card_id')) {
            if (!$existingFks->contains('payment_histories_card_id_foreign')) {
                Schema::table('payment_histories', function (Blueprint $table) {
                    $table->foreign('card_id')->references('id')->on('cards')->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 後方互換は不要
    }
};
