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
        Schema::create('group_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->string('title', 255);
            $table->text('content')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_new')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->boolean('send_notification')->default(false);
            $table->unsignedBigInteger('creator');
            $table->string('thumbnail')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('creator')->references('id')->on('users')->onDelete('cascade');//
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_posts');
    }
};
