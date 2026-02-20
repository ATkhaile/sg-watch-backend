<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->string('type', 20)->default('login')->after('code');
            $table->unsignedTinyInteger('attempts')->default(0)->after('is_used');
        });
    }

    public function down(): void
    {
        Schema::table('email_verification_codes', function (Blueprint $table) {
            $table->dropColumn(['type', 'attempts']);
        });
    }
};
