<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get current foreign key constraints
        $foreignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'slot_options'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        // Drop foreign key constraints first
        Schema::table('slot_options', function (Blueprint $table) use ($foreignKeys) {
            if ($foreignKeys->contains('slot_options_schedule_id_foreign')) {
                $table->dropForeign('slot_options_schedule_id_foreign');
            }
            if ($foreignKeys->contains('slot_options_shop_id_foreign')) {
                $table->dropForeign('slot_options_shop_id_foreign');
            }
        });

        // Get current indexes
        $indexes = collect(DB::select("SHOW INDEX FROM slot_options"))->pluck('Key_name')->unique();

        // Drop old indexes
        Schema::table('slot_options', function (Blueprint $table) use ($indexes) {
            if ($indexes->contains('options_schedule_id_foreign')) {
                $table->dropIndex('options_schedule_id_foreign');
            }
            if ($indexes->contains('options_shop_id_foreign')) {
                $table->dropIndex('options_shop_id_foreign');
            }
        });

        // Rename schedule_id column to availability_slot_id
        if (Schema::hasColumn('slot_options', 'schedule_id')) {
            Schema::table('slot_options', function (Blueprint $table) {
                $table->renameColumn('schedule_id', 'availability_slot_id');
            });
        }

        // Add new indexes and foreign key constraints
        Schema::table('slot_options', function (Blueprint $table) {
            $table->index('availability_slot_id', 'slot_options_availability_slot_id_index');
            $table->index('location_id', 'slot_options_location_id_index');
            $table->foreign('availability_slot_id')->references('id')->on('availability_slots')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints and indexes
        Schema::table('slot_options', function (Blueprint $table) {
            $table->dropForeign(['availability_slot_id']);
            $table->dropForeign(['location_id']);
            $table->dropIndex('slot_options_availability_slot_id_index');
            $table->dropIndex('slot_options_location_id_index');
        });

        // Rename columns back
        if (Schema::hasColumn('slot_options', 'availability_slot_id')) {
            Schema::table('slot_options', function (Blueprint $table) {
                $table->renameColumn('availability_slot_id', 'schedule_id');
            });
        }

        // Restore original indexes
        Schema::table('slot_options', function (Blueprint $table) {
            $table->index('schedule_id', 'options_schedule_id_foreign');
            $table->index('location_id', 'options_shop_id_foreign');
        });
    }
};
