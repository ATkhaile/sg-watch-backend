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
        Schema::table('post_comments',function(Blueprint $table){
            $table->foreign('parent_id')->references('id')->on('post_comments')->onDelete('cascade');
            $table->foreign('reply_id')->references('id')->on('post_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            // drop foreign keys
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['reply_id']);
        });   
    }
};
