<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint first
        Schema::table('tdbs_system_settings', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });

        // Rename the table
        Schema::rename('tdbs_system_settings', 'contract_documents');

        // Add new columns and rename type column
        Schema::table('contract_documents', function (Blueprint $table) {
            // Rename type to document_type
            $table->renameColumn('type', 'document_type');
        });

        Schema::table('contract_documents', function (Blueprint $table) {
            // Add new columns after document_type
            $table->string('name')->nullable()->after('document_type');
            $table->string('slug')->nullable()->after('name');
            $table->string('version')->nullable()->after('content');
            $table->boolean('is_active')->default(true)->after('version');
            $table->dateTime('published_at')->nullable()->after('is_active');

            // Restore foreign key constraint with new table name
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint
        Schema::table('contract_documents', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });

        // Remove added columns
        Schema::table('contract_documents', function (Blueprint $table) {
            $table->dropColumn(['name', 'slug', 'version', 'is_active', 'published_at']);
        });

        // Rename document_type back to type
        Schema::table('contract_documents', function (Blueprint $table) {
            $table->renameColumn('document_type', 'type');
        });

        // Rename table back
        Schema::rename('contract_documents', 'tdbs_system_settings');

        // Restore original foreign key constraint
        Schema::table('tdbs_system_settings', function (Blueprint $table) {
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }
};
