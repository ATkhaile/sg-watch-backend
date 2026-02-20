<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_sns_bot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('my_sns_id');
            $table->string('channel_id');
            $table->string('channel_secret');
            $table->string('channel_access_token');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('my_sns_id')->references('id')->on('my_sns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_sns_bot');
    }
};
