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
        Schema::create('video_call_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('channel_name')->unique();
            $table->enum('type', ['user', 'group']);
            $table->unsignedBigInteger('initiator_id');
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->enum('status', ['initiated', 'ongoing', 'ended', 'rejected', 'missed'])->default('initiated');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->timestamps();

            $table->foreign('initiator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('chat_groups')->onDelete('cascade');

            $table->index('channel_name');
            $table->index('status');
            $table->index(['initiator_id', 'receiver_id']);
            $table->index('group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_call_sessions');
    }
};
