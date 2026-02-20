<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQLでENUM値を変更するためにはALTER TABLEを使用
        DB::statement("ALTER TABLE external_costs MODIFY COLUMN category ENUM('outsourcing', 'materials', 'tools', 'services', 'other') COMMENT 'カテゴリ'");
    }

    public function down(): void
    {
        // 元のENUM値に戻す
        DB::statement("ALTER TABLE external_costs MODIFY COLUMN category ENUM('dev', 'design', 'qa', 'translation', 'other') COMMENT 'カテゴリ'");
    }
};