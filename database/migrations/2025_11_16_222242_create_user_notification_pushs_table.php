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
        Schema::create('user_notification_pushs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_push_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('notification_push_id')
                ->references('id')->on('notification_pushs')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_pushs');
    }
};
