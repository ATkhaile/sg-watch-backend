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
            $table->unsignedBigInteger('media_upload_id')->nullable()->after('content');
            $table->foreign('media_upload_id')->references('id')->on('media_uploads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->dropForeign(['media_upload_id']);
            $table->dropColumn('media_upload_id');
        });
    }
};
