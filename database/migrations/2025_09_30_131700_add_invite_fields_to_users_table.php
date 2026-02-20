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
        Schema::table('users', function (Blueprint $table) {
            $table->string('invite_code', 255)->unique()->nullable()->after('referral_code');
            $table->unsignedBigInteger('inviter_id')->nullable()->after('invite_code');
            $table->timestamp('invited_at')->nullable()->after('inviter_id');
            
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['inviter_id']);
            $table->dropColumn(['invite_code', 'inviter_id', 'invited_at']);
        });
    }
};