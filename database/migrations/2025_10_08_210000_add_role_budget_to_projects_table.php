<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('role_budget_percentages')->nullable()->comment('役割別工数予算配分（DS,QA,PM,PMO,PG,SE）')->after('planned_hours');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('role_budget_percentages');
        });
    }
};