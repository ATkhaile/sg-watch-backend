<?php

use App\Enums\PushType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('push_type', PushType::getValues())->change();
        });
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->enum('push_type', PushType::getValues())->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
