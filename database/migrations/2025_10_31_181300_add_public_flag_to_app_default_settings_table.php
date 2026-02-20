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
        Schema::table('app_default_settings', function (Blueprint $table) {
            $table->boolean('public_flag')->default(true)->after('link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_default_settings', function (Blueprint $table) {
            $table->dropColumn('public_flag');
        });
    }
};
