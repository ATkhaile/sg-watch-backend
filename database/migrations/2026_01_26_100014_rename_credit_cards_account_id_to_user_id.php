<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * credit_cards テーブルの account_id → user_id へリネーム
     */
    public function up(): void
    {
        // Drop foreign key constraint to tdbs_accounts first (if exists)
        $foreignKeys = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'credit_cards'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        if ($foreignKeys->isNotEmpty()) {
            Schema::table('credit_cards', function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $fk) {
                    $table->dropForeign($fk);
                }
            });
        }

        // Rename column using raw SQL
        if (Schema::hasColumn('credit_cards', 'account_id')) {
            DB::statement('ALTER TABLE credit_cards CHANGE account_id user_id BIGINT UNSIGNED NOT NULL');
        }

        // Add new foreign key constraint to users table
        $existingFks = collect(DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'credit_cards'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        "))->pluck('CONSTRAINT_NAME');

        Schema::table('credit_cards', function (Blueprint $table) use ($existingFks) {
            if (!$existingFks->contains('credit_cards_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 後方互換は不要
    }
};
