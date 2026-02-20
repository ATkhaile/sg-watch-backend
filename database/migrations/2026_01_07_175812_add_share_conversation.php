<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ai_message_conversations', function (Blueprint $table) {
            $table->uuid()->nullable();
            $table->boolean('is_shared')->default(false);
        });
        DB::table('ai_message_conversations')
            ->whereNull('uuid')
            ->update([
                'uuid' => DB::raw('(UUID())')
            ]);
        Schema::table('ai_message_conversations', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_message_conversations', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('is_shared');
        });
    }
};
