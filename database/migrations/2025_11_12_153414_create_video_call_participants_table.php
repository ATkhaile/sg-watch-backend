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
        Schema::create('video_call_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_call_session_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_audio_enabled')->default(true);
            $table->boolean('is_video_enabled')->default(true);
            $table->timestamps();

            $table->foreign('video_call_session_id')->references('id')->on('video_call_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('video_call_session_id');
            $table->index('user_id');

            $table->unique(['video_call_session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_call_participants');
    }
};
