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
        Schema::create('community_post_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_post_id')->constrained('community_posts')->onDelete('cascade');
            $table->foreignId('mentioned_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['community_post_id', 'mentioned_user_id'], 'cp_mentions_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_post_mentions');
    }
};
