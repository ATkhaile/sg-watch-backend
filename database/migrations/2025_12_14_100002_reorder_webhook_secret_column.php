<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // webhook_secretをsecret_keyの後に移動
        DB::statement('ALTER TABLE stripe_accounts MODIFY COLUMN webhook_secret VARCHAR(255) NULL AFTER secret_key');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 元の位置に戻す（updater_idの後）
        DB::statement('ALTER TABLE stripe_accounts MODIFY COLUMN webhook_secret VARCHAR(255) NULL AFTER updater_id');
    }
};
