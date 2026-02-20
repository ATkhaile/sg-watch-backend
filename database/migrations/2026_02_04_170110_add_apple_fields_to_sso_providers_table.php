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
        Schema::table('sso_providers', function (Blueprint $table) {
            $table->string('apple_team_id')->nullable()->after('scopes');
            $table->string('apple_private_key')->nullable()->after('apple_team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sso_providers', function (Blueprint $table) {
            $table->dropColumn(['apple_team_id', 'apple_private_key']);
        });
    }
};
