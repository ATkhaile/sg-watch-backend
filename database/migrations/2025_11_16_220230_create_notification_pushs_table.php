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
        Schema::create('notification_pushs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('img_path')->nullable();
            $table->boolean('all_user_flag')->default(true);
            $table->boolean('push_now_flag')->default(true);
            $table->dateTime('push_schedule')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_pushs');
    }
};
