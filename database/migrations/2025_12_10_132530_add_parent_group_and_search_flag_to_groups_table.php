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
        Schema::table('groups', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_group_id')
                ->nullable()
                ->after('created_user_id');

            $table->boolean('search_flag')
                ->nullable()
                ->default(false)
                ->after('private_flag');

            $table->foreign('parent_group_id')
                ->references('id')
                ->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['parent_group_id']);
            $table->dropColumn(['parent_group_id', 'search_flag']);
        });
    }
};
