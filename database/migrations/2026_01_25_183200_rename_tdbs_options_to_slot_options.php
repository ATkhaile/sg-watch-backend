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
        // Drop foreign key constraints first (tables referencing tdbs_options)
        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->dropForeign(['option_id']);
            $table->dropForeign(['option_type1_id']);
            $table->dropForeign(['option_type2_id']);
            $table->dropForeign(['usage_option_id']);
        });

        // Drop foreign key constraints on tdbs_options itself
        Schema::table('tdbs_options', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropForeign(['shop_id']);
        });

        // Rename the table
        Schema::rename('tdbs_options', 'slot_options');

        // Restore foreign key constraints on slot_options (pointing to new table names)
        Schema::table('slot_options', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('availability_slots')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });

        // Restore foreign key constraints from tdbs_reservations
        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->foreign('option_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('option_type1_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('option_type2_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('usage_option_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get current foreign key constraints on slot_options
        $foreignKeys = collect(\Illuminate\Support\Facades\DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'slot_options'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        // Drop foreign key constraints from tdbs_reservations (if exists)
        if (Schema::hasTable('tdbs_reservations')) {
            $tdbsResFks = collect(\Illuminate\Support\Facades\DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'tdbs_reservations'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            "))->pluck('CONSTRAINT_NAME');

            Schema::table('tdbs_reservations', function (Blueprint $table) use ($tdbsResFks) {
                if ($tdbsResFks->contains('tdbs_reservations_option_id_foreign')) {
                    $table->dropForeign('tdbs_reservations_option_id_foreign');
                }
                if ($tdbsResFks->contains('tdbs_reservations_option_type1_id_foreign')) {
                    $table->dropForeign('tdbs_reservations_option_type1_id_foreign');
                }
                if ($tdbsResFks->contains('tdbs_reservations_option_type2_id_foreign')) {
                    $table->dropForeign('tdbs_reservations_option_type2_id_foreign');
                }
                if ($tdbsResFks->contains('tdbs_reservations_usage_option_id_foreign')) {
                    $table->dropForeign('tdbs_reservations_usage_option_id_foreign');
                }
            });
        }

        // Drop foreign key constraints on slot_options (check current column names)
        Schema::table('slot_options', function (Blueprint $table) use ($foreignKeys) {
            // Check for both old and new column names
            if ($foreignKeys->contains('slot_options_schedule_id_foreign')) {
                $table->dropForeign('slot_options_schedule_id_foreign');
            }
            if ($foreignKeys->contains('slot_options_availability_slot_id_foreign')) {
                $table->dropForeign('slot_options_availability_slot_id_foreign');
            }
            if ($foreignKeys->contains('slot_options_shop_id_foreign')) {
                $table->dropForeign('slot_options_shop_id_foreign');
            }
            if ($foreignKeys->contains('slot_options_location_id_foreign')) {
                $table->dropForeign('slot_options_location_id_foreign');
            }
        });

        // Rename back (if not already named tdbs_options)
        if (Schema::hasTable('slot_options') && !Schema::hasTable('tdbs_options')) {
            Schema::rename('slot_options', 'tdbs_options');
        }

        // Restore original foreign key constraints on tdbs_options (if referenced tables exist)
        if (Schema::hasTable('tdbs_schedules') && Schema::hasTable('tdbs_shops')) {
            Schema::table('tdbs_options', function (Blueprint $table) {
                $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
                $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
            });
        }

        // Restore original foreign key constraints from tdbs_reservations (if exists)
        if (Schema::hasTable('tdbs_reservations') && Schema::hasTable('tdbs_options')) {
            Schema::table('tdbs_reservations', function (Blueprint $table) {
                $table->foreign('option_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
                $table->foreign('option_type1_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
                $table->foreign('option_type2_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
                $table->foreign('usage_option_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
            });
        }
    }
};
