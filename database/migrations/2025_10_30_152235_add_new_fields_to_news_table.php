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
        Schema::table('news', function (Blueprint $table) {
            $table->string('short_description', 500)->nullable()->after('content')->comment('Short description');
            $table->boolean('is_important')->default(false)->after('short_description')->comment('Is important flag');
            $table->boolean('is_new')->default(false)->after('is_important')->comment('Is new flag');
            $table->dateTime('published_at')->nullable()->after('status')->comment('Published date and time');
            $table->boolean('send_notification')->default(false)->after('published_at')->comment('Send notification flag');
            $table->string('thumbnail')->nullable()->after('send_notification')->comment('Thumbnail image path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'is_important',
                'is_new',
                'published_at',
                'send_notification',
                'thumbnail'
            ]);
        });
    }
};
