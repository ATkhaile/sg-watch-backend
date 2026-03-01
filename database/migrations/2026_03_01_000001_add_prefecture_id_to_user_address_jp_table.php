<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old prefecture_id column if exists (wrong type from failed migration)
        if (Schema::hasColumn('user_address_jp', 'prefecture_id')) {
            Schema::table('user_address_jp', function (Blueprint $table) {
                $table->dropColumn('prefecture_id');
            });
        }

        Schema::table('user_address_jp', function (Blueprint $table) {
            $table->char('prefecture_id', 26)->nullable()->after('address_id');
            $table->foreign('prefecture_id')->references('prefecture_id')->on('prefectures')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_address_jp', function (Blueprint $table) {
            $table->dropForeign(['prefecture_id']);
            $table->dropColumn('prefecture_id');
        });
    }
};
