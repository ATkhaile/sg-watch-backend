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
        Schema::create('community_post_attachs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('community_post_id');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('community_post_id')
                ->references('id')->on('community_posts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_post_attachs');
    }
};
