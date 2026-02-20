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
        Schema::table('ai_applications', function (Blueprint $table) {
            $table->boolean('enable_mcp_tools')->default(false)->after('tools')
                ->comment('Enable MCP tools via execute_domain_action meta-tool');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_applications', function (Blueprint $table) {
            $table->dropColumn('enable_mcp_tools');
        });
    }
};
