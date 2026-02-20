<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_release_id');
            $table->string('version_name');
            $table->date('release_date');
            $table->boolean('min_version_flag')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('app_release_id')
                ->references('id')->on('app_releases')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
