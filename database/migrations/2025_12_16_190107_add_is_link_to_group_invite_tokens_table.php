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
        Schema::table('group_invite_tokens', function (Blueprint $table) {
            $table->boolean('is_link')->default(false)->after('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_invite_tokens', function (Blueprint $table) {
            $table->dropColumn('is_link');
        });
    }
};
