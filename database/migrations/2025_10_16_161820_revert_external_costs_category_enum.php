<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // まずカラムを一時的にVARCHARに変更
        DB::statement("ALTER TABLE external_costs MODIFY COLUMN category VARCHAR(50)");

        // 既存のデータを新しいENUM値にマッピング
        DB::table('external_costs')
            ->where('category', 'outsourcing')
            ->update(['category' => 'dev']);

        DB::table('external_costs')
            ->where('category', 'materials')
            ->update(['category' => 'other']);

        DB::table('external_costs')
            ->where('category', 'tools')
            ->update(['category' => 'other']);

        DB::table('external_costs')
            ->where('category', 'services')
            ->update(['category' => 'other']);

        // MySQLでENUM値を元の値に設定
        DB::statement("ALTER TABLE external_costs MODIFY COLUMN category ENUM('dev', 'design', 'qa', 'translation', 'other') COMMENT 'カテゴリ'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ロールバック時は新しいENUMに戻す
        DB::statement("ALTER TABLE external_costs MODIFY COLUMN category ENUM('outsourcing', 'materials', 'tools', 'services', 'other') COMMENT 'カテゴリ'");
    }
};
