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
        Schema::create('group_post_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_post_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('group_post_id')->references('id')->on('group_posts');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_post_tags');
    }
};
