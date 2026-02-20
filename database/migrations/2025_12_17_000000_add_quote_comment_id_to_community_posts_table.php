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
        Schema::table('community_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_comment_id')->nullable()->after('quote_id');
            $table->foreign('quote_comment_id')->references('id')->on('community_post_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->dropForeign(['quote_comment_id']);
            $table->dropColumn('quote_comment_id');
        });
    }
};
