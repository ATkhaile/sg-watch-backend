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
            $table->string('tenant_id', 255)->nullable()->after('scopes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sso_providers', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};
