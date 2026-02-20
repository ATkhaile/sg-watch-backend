<?php

use App\Enums\PostActionType;
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
        Schema::table('community_post_actions', function (Blueprint $table) {
            $table->enum('action', ['not_interested', 'report', 'mute', 'pin'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_post_actions', function (Blueprint $table) {
            //
        });
    }
};
