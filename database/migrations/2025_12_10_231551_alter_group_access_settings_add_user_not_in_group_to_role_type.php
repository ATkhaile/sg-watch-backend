<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `group_access_settings`
            MODIFY `role_type` ENUM('owner','admin','member','user_not_in_group') NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE `group_access_settings`
            MODIFY `role_type` ENUM('owner','admin','member') NOT NULL
        ");
    }
};
