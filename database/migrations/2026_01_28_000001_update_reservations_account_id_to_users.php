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
        Schema::table('reservations', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['account_id']);

            // Add new foreign key constraint referencing users table
            $table->foreign('account_id')->references('id')->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['account_id']);

            // Restore the old foreign key constraint
            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
        });
    }
};
