<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PointMasterType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->enum('point_type', PointMasterType::getValues())->nullable()->after('last_update_user_id');
            $table->boolean('show_popup_flag')->default(false)->after('point_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_histories', function (Blueprint $table) {
            $table->dropColumn(['point_type', 'show_popup_flag']);
        });
    }
};
