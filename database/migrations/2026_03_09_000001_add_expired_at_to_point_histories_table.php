<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable()->after('show_popup_flag');
            $table->bigInteger('remaining_point')->default(0)->after('expired_at');
        });

        // Backfill: set expired_at = created_at + 6 months, remaining_point = point
        DB::table('point_histories')
            ->where('point', '>', 0)
            ->update([
                'expired_at' => DB::raw("DATE_ADD(created_at, INTERVAL 6 MONTH)"),
                'remaining_point' => DB::raw("point"),
            ]);
    }

    public function down(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->dropColumn(['expired_at', 'remaining_point']);
        });
    }
};
