<?php

use App\Enums\RoleType;
use App\Enums\UserGroupRoleType;
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
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropColumn('role_type');
        });
        Schema::table('user_groups', function (Blueprint $table) {
            $table->enum('role_type', RoleType::getValues())->default(RoleType::OWNER)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
