<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('columns_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('columns_id')->comment('ニュースのテープルにid');
            $table->unsignedBigInteger('tag_id')->comment('タグのテープルにid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns_tags');
    }
};
