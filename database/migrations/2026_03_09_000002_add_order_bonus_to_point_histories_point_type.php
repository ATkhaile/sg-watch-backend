<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        DB::statement("ALTER TABLE point_histories MODIFY COLUMN point_type ENUM('daily_bonus', 'affiliate_bonus', 'order_bonus') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE point_histories MODIFY COLUMN point_type ENUM('daily_bonus', 'affiliate_bonus') NULL");
    }
};
