<?php

use Illuminate\Support\Facades\DB;

return new class extends \Illuminate\Database\Migrations\Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum column to include both 'shift_enter' and 'ctrl_enter'
        DB::statement("ALTER TABLE user_community_settings MODIFY COLUMN send_method ENUM('enter', 'shift_enter', 'ctrl_enter') NOT NULL DEFAULT 'shift_enter'");

        // Update existing 'shift_enter' values to 'ctrl_enter'
        DB::table('user_community_settings')
            ->where('send_method', 'shift_enter')
            ->update(['send_method' => 'ctrl_enter']);

        // Finally, remove 'shift_enter' from the enum and set new default
        DB::statement("ALTER TABLE user_community_settings MODIFY COLUMN send_method ENUM('enter', 'ctrl_enter') NOT NULL DEFAULT 'ctrl_enter'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'ctrl_enter' values back to 'shift_enter'
        DB::table('user_community_settings')
            ->where('send_method', 'ctrl_enter')
            ->update(['send_method' => 'shift_enter']);

        // Revert the enum column back to original values
        DB::statement("ALTER TABLE user_community_settings MODIFY COLUMN send_method ENUM('enter', 'shift_enter') NOT NULL DEFAULT 'shift_enter'");
    }
};
