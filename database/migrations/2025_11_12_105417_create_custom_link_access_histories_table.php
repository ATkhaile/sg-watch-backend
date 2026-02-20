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
        Schema::create('custom_link_access_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_link_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('referral_source')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('custom_link_id')->references('id')->on('custom_links');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_link_access_histories');
    }
};
