<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->string('code')->after('id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('code')->after('id');
        });
    }
};
