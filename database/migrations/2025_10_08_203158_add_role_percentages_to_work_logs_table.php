<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->json('role_percentages')->nullable()->comment('役割別工数配分（DS,QA,PM,PMO,PG,SE）')->after('category');
            $table->enum('primary_role', ['DS', 'QA', 'PM', 'PMO', 'PG', 'SE'])->nullable()->comment('主要役割')->after('role_percentages');
        });
    }

    public function down(): void
    {
        Schema::table('work_logs', function (Blueprint $table) {
            $table->dropColumn(['role_percentages', 'primary_role']);
        });
    }
};