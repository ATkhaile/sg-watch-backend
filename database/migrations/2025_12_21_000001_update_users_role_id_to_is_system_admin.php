<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * role_id (1=CUSTOMER, 2=ADMIN) を is_system_admin (0 or 1) に変換
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 新しいカラムを追加
            if (!Schema::hasColumn('users', 'is_system_admin')) {
                $table->boolean('is_system_admin')->default(false)->after('role_id')->comment('システム管理者フラグ');
            }
        });

        // 既存データの移行: role_id = 2 (ADMIN) の場合は is_system_admin = 1
        if (Schema::hasColumn('users', 'role_id')) {
            DB::table('users')->where('role_id', 2)->update(['is_system_admin' => true]);
        }

        // role_idカラムを削除
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropColumn('role_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role_idカラムを復元
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedInteger('role_id')->default(1)->after('is_system_admin');
            }
        });

        // is_system_admin = 1 の場合は role_id = 2 (ADMIN)
        if (Schema::hasColumn('users', 'is_system_admin')) {
            DB::table('users')->where('is_system_admin', true)->update(['role_id' => 2]);
            DB::table('users')->where('is_system_admin', false)->update(['role_id' => 1]);
        }

        // is_system_adminカラムを削除
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_system_admin')) {
                $table->dropColumn('is_system_admin');
            }
        });
    }
};
