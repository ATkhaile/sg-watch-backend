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
        Schema::create('community_post_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_post_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('comment')->nullable();
            $table->string('file_attach')->nullable();
            $table->unsignedBigInteger('reply_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('community_post_id')
                ->references('id')->on('community_posts')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')->on('community_post_comments')
                ->onDelete('cascade');

            $table->foreign('reply_id')
                ->references('id')->on('community_post_comments')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_post_comments');
    }
};
