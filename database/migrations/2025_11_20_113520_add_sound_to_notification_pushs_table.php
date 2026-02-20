<?php

use App\Enums\IOSSystemSound;
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
            $table->enum('sound', IOSSystemSound::getValues())->default('default')
                ->after('img_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_pushs', function (Blueprint $table) {
            $table->dropColumn('sound');
        });
    }
};
