<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // stripe_createdをbusiness_profile_product_descriptionの後に移動
        DB::statement('ALTER TABLE stripe_accounts MODIFY COLUMN stripe_created TIMESTAMP NULL AFTER business_profile_product_description');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 元の位置に戻す（created_atの後）
        DB::statement('ALTER TABLE stripe_accounts MODIFY COLUMN stripe_created TIMESTAMP NULL AFTER created_at');
    }
};
