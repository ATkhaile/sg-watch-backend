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
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->string('device_name')->nullable()->after('user_id');
            $table->unsignedBigInteger('app_version_id')->nullable()->after('device_name');

            $table->foreign('app_version_id')->references('id')->on('app_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->dropForeign(['app_version_id']);
            $table->dropColumn(['device_name', 'app_version_id']);
        });
    }
};
