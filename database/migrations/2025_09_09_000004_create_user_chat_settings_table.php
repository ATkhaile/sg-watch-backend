<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_chat_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('chat_partner_id');
            $table->string('custom_name')->nullable();
            $table->string('custom_avatar')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->integer('sort_order')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_partner_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'chat_partner_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_chat_settings');
    }
};