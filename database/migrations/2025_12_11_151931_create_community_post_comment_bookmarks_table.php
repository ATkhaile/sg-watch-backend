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
        Schema::create('community_post_comment_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_post_comment_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('community_post_comment_id', 'cpc_bm_comment_fk')
                ->references('id')
                ->on('community_post_comments');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_post_comment_bookmarks');
    }
};
