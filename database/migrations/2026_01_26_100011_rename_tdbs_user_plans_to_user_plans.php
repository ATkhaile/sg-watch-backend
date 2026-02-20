<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * tdbs_user_plans → user_plans へリネーム
     * shop_id → location_id、account_id → user_id へリネーム
     * plan_id は plan_reservation_rules への参照に変更
     */
    public function up(): void
    {
        $tableName = Schema::hasTable('user_plans') ? 'user_plans' : 'tdbs_user_plans';

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
        if (Schema::hasTable('tdbs_user_plans') && !Schema::hasTable('user_plans')) {
            Schema::rename('tdbs_user_plans', 'user_plans');
        }

        // Rename columns using raw SQL (avoid Doctrine DBAL issues) - check if column exists first
        if (Schema::hasColumn('user_plans', 'shop_id')) {
            DB::statement('ALTER TABLE user_plans CHANGE shop_id location_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('user_plans', 'account_id')) {
            DB::statement('ALTER TABLE user_plans CHANGE account_id user_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('user_plans', 'plan_id')) {
            DB::statement('ALTER TABLE user_plans CHANGE plan_id plan_reservation_rule_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('user_plans', 'next_month_plan_id')) {
            DB::statement('ALTER TABLE user_plans CHANGE next_month_plan_id next_month_rule_id BIGINT UNSIGNED NULL');
        }

        // Add new foreign key constraints (check if they don't already exist)
        $existingFks = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'user_plans'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        Schema::table('user_plans', function (Blueprint $table) use ($existingFks) {
            if (!$existingFks->contains('user_plans_location_id_foreign')) {
                $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            }
            if (!$existingFks->contains('user_plans_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!$existingFks->contains('user_plans_plan_reservation_rule_id_foreign')) {
                $table->foreign('plan_reservation_rule_id')->references('id')->on('plan_reservation_rules')->onDelete('restrict');
            }
            if (!$existingFks->contains('user_plans_next_month_rule_id_foreign')) {
                $table->foreign('next_month_rule_id')->references('id')->on('plan_reservation_rules')->onDelete('set null');
            }
        });

        // Add card_id foreign key if cards table exists
        if (Schema::hasTable('cards') && Schema::hasColumn('user_plans', 'next_payment_card_id')) {
            if (!$existingFks->contains('user_plans_next_payment_card_id_foreign')) {
                Schema::table('user_plans', function (Blueprint $table) {
                    $table->foreign('next_payment_card_id')->references('id')->on('cards')->onDelete('set null');
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
