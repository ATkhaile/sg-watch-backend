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
        Schema::create('prefectures', function (Blueprint $table) {
            $table->ulid('prefecture_id')->primary();
            $table->string('name')->comment('都道府県名');
            $table->integer('order_num')->comment('表示順');
            $table->timestamps();
            $table->softDeletes();
            $table->index('prefecture_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefectures');
    }
};
