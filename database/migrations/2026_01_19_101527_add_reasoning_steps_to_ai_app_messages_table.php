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
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->json('reasoning_steps')->nullable()->after('response_metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->dropColumn('reasoning_steps');
        });
    }
};
