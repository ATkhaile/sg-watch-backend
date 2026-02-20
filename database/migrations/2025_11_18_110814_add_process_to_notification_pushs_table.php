<?php

use App\Enums\NotificationPushProcess;
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
        Schema::table('notification_pushs', function (Blueprint $table) {
            $table->enum('process', NotificationPushProcess::getValues())->default('waiting')
                ->after('push_now_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_pushs', function (Blueprint $table) {
            $table->dropColumn('process');
        });
    }
};
