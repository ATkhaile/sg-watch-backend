<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('email_verifications', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('email');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Migrate name → first_name/last_name
        DB::statement("
            UPDATE email_verifications SET
                first_name = SUBSTRING_INDEX(name, ' ', 1),
                last_name = CASE
                    WHEN LOCATE(' ', name) > 0
                    THEN TRIM(SUBSTRING(name, LOCATE(' ', name) + 1))
                    ELSE ''
                END
            WHERE name IS NOT NULL
        ");

        Schema::table('email_verifications', function (Blueprint $table) {
            $table->dropColumn('name');

            if (Schema::hasColumn('email_verifications', 'affiliate_id')) {
                $table->dropColumn('affiliate_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('email_verifications', function (Blueprint $table) {
            $table->string('name')->nullable()->after('email');
            $table->unsignedBigInteger('affiliate_id')->nullable();
        });

        DB::statement("UPDATE email_verifications SET name = CONCAT(first_name, ' ', last_name)");

        Schema::table('email_verifications', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
