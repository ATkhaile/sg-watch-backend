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
        Schema::create('chat_group_invite_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('invited_user_id');    // User being invited
            $table->unsignedBigInteger('requested_by');        // Member who sent the invite request
            $table->text('message')->nullable();               // Optional message/reason
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('responded_by')->nullable();  // Admin/Owner who responded
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('chat_groups')->onDelete('cascade');
            $table->foreign('invited_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');

            // Prevent duplicate pending requests for the same user in the same group
            $table->index(['group_id', 'invited_user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_group_invite_requests');
    }
};
