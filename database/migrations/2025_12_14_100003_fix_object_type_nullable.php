<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // object_typeをNULL許可に変更し、デフォルト値を設定
        DB::statement("ALTER TABLE stripe_accounts MODIFY COLUMN object_type VARCHAR(50) NULL DEFAULT 'account' COMMENT 'Stripe object type'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 元に戻す（NOT NULL）
        DB::statement("ALTER TABLE stripe_accounts MODIFY COLUMN object_type VARCHAR(50) NOT NULL DEFAULT 'account' COMMENT 'Stripe object type'");
    }
};
