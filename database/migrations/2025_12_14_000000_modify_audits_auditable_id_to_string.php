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
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Drop the index first
            $table->dropIndex(['auditable_type', 'auditable_id']);

            // Change auditable_id from unsignedBigInteger to string(36) to support ULIDs
            $table->string('auditable_id', 36)->change();

            // Recreate the index
            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            $table->dropIndex(['auditable_type', 'auditable_id']);
            $table->unsignedBigInteger('auditable_id')->change();
            $table->index(['auditable_type', 'auditable_id']);
        });
    }
};
