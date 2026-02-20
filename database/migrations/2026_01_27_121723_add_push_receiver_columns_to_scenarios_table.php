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
        Schema::table('scenarios', function (Blueprint $table) {
            $table->unsignedBigInteger('push_receiver_id')->nullable()->after('description');
            $table->unsignedBigInteger('firebase_receiver_id')->nullable()->after('push_receiver_id');

            $table->foreign('push_receiver_id')
                ->references('id')->on('pusher_infos');

            $table->foreign('firebase_receiver_id')
                ->references('id')->on('pusher_infos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scenarios', function (Blueprint $table) {
            $table->dropForeign(['firebase_receiver_id']);
            $table->dropForeign(['push_receiver_id']);

            $table->dropColumn(['push_receiver_id', 'firebase_receiver_id']);
        });
    }
};
