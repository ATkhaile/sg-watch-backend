<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * ロールを2種類に簡素化: admin（管理者）, user（一般ユーザー）
     * is_system_owner カラムを削除
     */
    public function up(): void
    {
        // 1. ロール名をリネーム: super_admin → admin, member → user
        DB::table('roles')->where('name', 'super_admin')->update([
            'name' => 'admin',
            'display_name' => '管理者',
            'description' => '全ての権限を持つ管理者ロール',
        ]);

        DB::table('roles')->where('name', 'member')->update([
            'name' => 'user',
            'display_name' => 'ユーザー',
            'description' => '一般ユーザー向けのデフォルトロール',
        ]);

        // 2. is_system_owner カラムを削除
        if (Schema::hasColumn('users', 'is_system_owner')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_system_owner');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. ロール名を元に戻す
        DB::table('roles')->where('name', 'admin')->update([
            'name' => 'super_admin',
            'display_name' => 'スーパー管理者',
            'description' => '全ての権限を持つスーパー管理者ロール',
        ]);

        DB::table('roles')->where('name', 'user')->update([
            'name' => 'member',
            'display_name' => 'メンバー',
            'description' => '一般メンバー向けのデフォルトロール（基本的な閲覧・操作権限）',
        ]);

        // 2. is_system_owner カラムを復元
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_system_owner')->default(false)->after('is_system_admin');
        });
    }
};
