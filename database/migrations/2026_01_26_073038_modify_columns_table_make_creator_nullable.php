<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * creatorカラムをNULLABLEに変更
     * Seederでcreator=nullを許可するため
     */
    public function up(): void
    {
        Schema::table('columns', function (Blueprint $table) {
            // 外部キー制約を一旦削除
            $table->dropForeign(['creator']);

            // creatorをNULLABLEに変更
            $table->unsignedBigInteger('creator')->nullable()->change();

            // 外部キー制約を再設定
            $table->foreign('creator')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 後方互換は不要なのでnullableのまま
    }
};
