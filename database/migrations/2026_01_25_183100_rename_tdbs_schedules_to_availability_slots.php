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
        // Drop foreign key constraints first (tables referencing tdbs_schedules)
        Schema::table('tdbs_options', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
        });

        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
        });

        // Rename the table
        Schema::rename('tdbs_schedules', 'availability_slots');

        // Restore foreign key constraints with new table name
        Schema::table('tdbs_options', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('availability_slots')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_reservations', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('availability_slots')->onUpdate('restrict')->onDelete('restrict');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('availability_slots')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Helper function to get foreign keys for a table
        $getForeignKeys = function ($tableName) {
            return collect(\Illuminate\Support\Facades\DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$tableName]))->pluck('CONSTRAINT_NAME');
        };

        // Drop foreign key constraints on tdbs_options (if table exists)
        if (Schema::hasTable('tdbs_options')) {
            $fks = $getForeignKeys('tdbs_options');
            Schema::table('tdbs_options', function (Blueprint $table) use ($fks) {
                if ($fks->contains('tdbs_options_schedule_id_foreign')) {
                    $table->dropForeign('tdbs_options_schedule_id_foreign');
                }
            });
        }

        // Drop foreign key constraints on tdbs_reservations (if table exists)
        if (Schema::hasTable('tdbs_reservations')) {
            $fks = $getForeignKeys('tdbs_reservations');
            Schema::table('tdbs_reservations', function (Blueprint $table) use ($fks) {
                if ($fks->contains('tdbs_reservations_schedule_id_foreign')) {
                    $table->dropForeign('tdbs_reservations_schedule_id_foreign');
                }
            });
        }

        // Drop foreign key constraints on tdbs_payment_histories (if table exists)
        if (Schema::hasTable('tdbs_payment_histories')) {
            $fks = $getForeignKeys('tdbs_payment_histories');
            Schema::table('tdbs_payment_histories', function (Blueprint $table) use ($fks) {
                if ($fks->contains('tdbs_payment_histories_schedule_id_foreign')) {
                    $table->dropForeign('tdbs_payment_histories_schedule_id_foreign');
                }
            });
        }

        // Rename back (if not already named tdbs_schedules)
        if (Schema::hasTable('availability_slots') && !Schema::hasTable('tdbs_schedules')) {
            Schema::rename('availability_slots', 'tdbs_schedules');
        }

        // Restore original foreign key constraints (only if referenced tables exist)
        if (Schema::hasTable('tdbs_schedules')) {
            if (Schema::hasTable('tdbs_options') && Schema::hasColumn('tdbs_options', 'schedule_id')) {
                Schema::table('tdbs_options', function (Blueprint $table) {
                    $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
                });
            }

            if (Schema::hasTable('tdbs_reservations') && Schema::hasColumn('tdbs_reservations', 'schedule_id')) {
                Schema::table('tdbs_reservations', function (Blueprint $table) {
                    $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
                });
            }

            if (Schema::hasTable('tdbs_payment_histories') && Schema::hasColumn('tdbs_payment_histories', 'schedule_id')) {
                Schema::table('tdbs_payment_histories', function (Blueprint $table) {
                    $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
                });
            }
        }
    }
};
