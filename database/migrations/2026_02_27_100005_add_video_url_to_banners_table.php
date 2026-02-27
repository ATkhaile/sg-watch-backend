<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->renameColumn('image_url', 'media_url');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->string('media_url')->nullable()->change();
            $table->string('media_type')->default('image')->after('media_url');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('media_type');
            $table->renameColumn('media_url', 'image_url');
        });
    }
};
