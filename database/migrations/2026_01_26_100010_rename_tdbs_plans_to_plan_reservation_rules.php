<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * tdbs_plans → plan_reservation_rules へリネーム
     * 共通化されたPlanと1:多の関係（plan.plan_id : plan_reservation_rules.plan_id）
     */
    public function up(): void
    {
        // Get current foreign key constraints on tdbs_plans
        $foreignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'tdbs_plans'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        // Drop foreign key constraints that reference tdbs_shops
        Schema::table('tdbs_plans', function (Blueprint $table) use ($foreignKeys) {
            if ($foreignKeys->contains('plans_shop_id_foreign')) {
                $table->dropForeign('plans_shop_id_foreign');
            }
            if ($foreignKeys->contains('tdbs_plans_shop_id_foreign')) {
                $table->dropForeign('tdbs_plans_shop_id_foreign');
            }
        });

        // Drop foreign keys from dependent tables before renaming
        if (Schema::hasTable('tdbs_user_plans')) {
            $userPlansFks = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'tdbs_user_plans'
                AND REFERENCED_TABLE_NAME = 'tdbs_plans'
            "))->pluck('CONSTRAINT_NAME');

            Schema::table('tdbs_user_plans', function (Blueprint $table) use ($userPlansFks) {
                foreach ($userPlansFks as $fk) {
                    $table->dropForeign($fk);
                }
            });
        }

        if (Schema::hasTable('tdbs_payment_plans')) {
            $paymentPlansFks = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'tdbs_payment_plans'
                AND REFERENCED_TABLE_NAME = 'tdbs_plans'
            "))->pluck('CONSTRAINT_NAME');

            Schema::table('tdbs_payment_plans', function (Blueprint $table) use ($paymentPlansFks) {
                foreach ($paymentPlansFks as $fk) {
                    $table->dropForeign($fk);
                }
            });
        }

        // Rename table
        if (Schema::hasTable('tdbs_plans') && !Schema::hasTable('plan_reservation_rules')) {
            Schema::rename('tdbs_plans', 'plan_reservation_rules');
        }

        // Rename shop_id to location_id using raw SQL (avoid Doctrine DBAL issues)
        if (Schema::hasColumn('plan_reservation_rules', 'shop_id')) {
            DB::statement('ALTER TABLE plan_reservation_rules CHANGE shop_id location_id BIGINT UNSIGNED NOT NULL');
        }

        // Add plan_id column for linking to common plans table
        if (!Schema::hasColumn('plan_reservation_rules', 'plan_id')) {
            Schema::table('plan_reservation_rules', function (Blueprint $table) {
                $table->string('plan_id', 26)->nullable()->after('id')->comment('共通Planへの紐付け');
            });
        }

        // Add foreign keys (check if they don't already exist)
        $existingFks = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'plan_reservation_rules'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        Schema::table('plan_reservation_rules', function (Blueprint $table) use ($existingFks) {
            // Add foreign key to locations
            if (!$existingFks->contains('plan_reservation_rules_location_id_foreign')) {
                $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            }

            // Add foreign key to plans
            if (!$existingFks->contains('plan_reservation_rules_plan_id_foreign')) {
                $table->foreign('plan_id')->references('plan_id')->on('plans')->onDelete('set null');
            }
        });

        // Add index if not exists
        $indexes = collect(DB::select("SHOW INDEX FROM plan_reservation_rules"))->pluck('Key_name')->unique();
        if (!$indexes->contains('prr_plan_id_idx')) {
            Schema::table('plan_reservation_rules', function (Blueprint $table) {
                $table->index('plan_id', 'prr_plan_id_idx');
            });
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
