<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_address_jp', function (Blueprint $table) {
            $table->string('building_name', 150)->nullable()->after('banchi')->comment('建物名');
        });
    }

    public function down(): void
    {
        Schema::table('user_address_jp', function (Blueprint $table) {
            $table->dropColumn('building_name');
        });
    }
};
