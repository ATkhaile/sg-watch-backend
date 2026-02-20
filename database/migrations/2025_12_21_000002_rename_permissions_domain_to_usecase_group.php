<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * permissionsテーブルのdomainカラムをusecase_groupにリネーム
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'domain') && !Schema::hasColumn('permissions', 'usecase_group')) {
                $table->renameColumn('domain', 'usecase_group');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'usecase_group') && !Schema::hasColumn('permissions', 'domain')) {
                $table->renameColumn('usecase_group', 'domain');
            }
        });
    }
};
