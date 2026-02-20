<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * user_entitlement_logs テーブルの action カラムに enable/disable を追加
     */
    public function up(): void
    {
        // MySQL の ENUM に新しい値を追加
        DB::statement("ALTER TABLE user_entitlement_logs MODIFY COLUMN action ENUM('grant', 'consume', 'reset', 'override', 'expire', 'revoke', 'enable', 'disable')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 元の ENUM に戻す（注意: enable/disable のログがあると失敗する可能性がある）
        DB::statement("ALTER TABLE user_entitlement_logs MODIFY COLUMN action ENUM('grant', 'consume', 'reset', 'override', 'expire', 'revoke')");
    }
};
