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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('model')->index(); // columns, news, etc.
            $table->unsignedBigInteger('model_id')->index(); // columns_id, news_id, etc.
            $table->unsignedBigInteger('user_id')->index();
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();

            // Composite index for model + model_id queries
            $table->index(['model', 'model_id']);

            // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
