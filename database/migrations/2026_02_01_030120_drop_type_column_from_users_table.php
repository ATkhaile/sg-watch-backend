<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * usersテーブルからtypeカラムを削除
     * ユーザー種別の区別は不要となったため
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'type')) {
                $table->dropIndex(['type']);
                $table->dropColumn('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'type')) {
                $table->string('type', 20)->default('admin')->after('uuid')->comment('ユーザータイプ: admin, member');
                $table->index('type');
            }
        });
    }
};
