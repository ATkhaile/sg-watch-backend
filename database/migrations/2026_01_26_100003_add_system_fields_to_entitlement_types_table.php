<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('entitlement_types', function (Blueprint $table) {
            $table->boolean('is_system')->default(false)->after('is_active')->comment('システム管理フラグ（true=削除不可）');
            $table->enum('controlled_by', ['server', 'client', 'both'])->default('both')->after('is_system')->comment('制御場所（server=サーバーのみ、client=クライアントのみ、both=両方）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entitlement_types', function (Blueprint $table) {
            $table->dropColumn(['is_system', 'controlled_by']);
        });
    }
};
