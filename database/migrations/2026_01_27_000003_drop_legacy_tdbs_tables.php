<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 不要になったレガシーtdbs_テーブルを削除
     */
    public function up(): void
    {
        // 外部キー制約チェックを一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // tdbs_reservations テーブルを削除（reservationsテーブルに移行済み）
        Schema::dropIfExists('tdbs_reservations');

        // tdbs_booking_tmps テーブルを削除
        Schema::dropIfExists('tdbs_booking_tmps');

        // tdbs_accounts テーブルを削除（usersテーブルに統合済み）
        Schema::dropIfExists('tdbs_accounts');

        // 外部キー制約チェックを再有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // レガシーテーブルは復元しない
    }
};
