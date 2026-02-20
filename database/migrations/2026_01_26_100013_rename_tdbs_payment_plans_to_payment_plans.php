<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * tdbs_payment_plans → payment_plans へリネーム
     * account_id → user_id へリネーム
     * plan_id → plan_reservation_rule_id へリネーム
     */
    public function up(): void
    {
        $tableName = Schema::hasTable('payment_plans') ? 'payment_plans' : 'tdbs_payment_plans';

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

        // Drop foreign keys from dependent tables (user_plans references payment_plans)
        if (Schema::hasTable('user_plans')) {
            $userPlansFks = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'user_plans'
                AND REFERENCED_TABLE_NAME = ?
            ", [$tableName]))->pluck('CONSTRAINT_NAME');

            if ($userPlansFks->isNotEmpty()) {
                Schema::table('user_plans', function (Blueprint $table) use ($userPlansFks) {
                    foreach ($userPlansFks as $fk) {
                        $table->dropForeign($fk);
                    }
                });
            }
        }

        // Rename table
        if (Schema::hasTable('tdbs_payment_plans') && !Schema::hasTable('payment_plans')) {
            Schema::rename('tdbs_payment_plans', 'payment_plans');
        }

        // Rename columns using raw SQL (avoid Doctrine DBAL issues) - check if column exists first
        if (Schema::hasColumn('payment_plans', 'account_id')) {
            DB::statement('ALTER TABLE payment_plans CHANGE account_id user_id BIGINT UNSIGNED NOT NULL');
        }
        if (Schema::hasColumn('payment_plans', 'plan_id')) {
            DB::statement('ALTER TABLE payment_plans CHANGE plan_id plan_reservation_rule_id BIGINT UNSIGNED NOT NULL');
        }

        // Add new foreign key constraints (check if they don't already exist)
        $existingFks = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'payment_plans'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        Schema::table('payment_plans', function (Blueprint $table) use ($existingFks) {
            if (!$existingFks->contains('payment_plans_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!$existingFks->contains('payment_plans_plan_reservation_rule_id_foreign')) {
                $table->foreign('plan_reservation_rule_id')->references('id')->on('plan_reservation_rules')->onDelete('restrict');
            }
        });

        // Add card_id foreign key if cards table exists
        if (Schema::hasTable('cards') && Schema::hasColumn('payment_plans', 'card_id')) {
            if (!$existingFks->contains('payment_plans_card_id_foreign')) {
                Schema::table('payment_plans', function (Blueprint $table) {
                    $table->foreign('card_id')->references('id')->on('cards')->onDelete('set null');
                });
            }
        }

        // Add coupon_id foreign key if coupons table exists
        if (Schema::hasTable('coupons') && Schema::hasColumn('payment_plans', 'coupon_id')) {
            if (!$existingFks->contains('payment_plans_coupon_id_foreign')) {
                Schema::table('payment_plans', function (Blueprint $table) {
                    $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
                });
            }
        }

        // Restore foreign key from user_plans to payment_plans
        if (Schema::hasTable('user_plans') && Schema::hasColumn('user_plans', 'payment_plan_id')) {
            $userPlansFks = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'user_plans'
                AND REFERENCED_TABLE_NAME = 'payment_plans'
            "))->pluck('CONSTRAINT_NAME');

            if (!$userPlansFks->contains('user_plans_payment_plan_id_foreign')) {
                Schema::table('user_plans', function (Blueprint $table) {
                    $table->foreign('payment_plan_id')->references('id')->on('payment_plans')->onDelete('set null');
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
