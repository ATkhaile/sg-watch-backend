<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\WorkManagementStatus;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_managements', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->dateTime('public_date')->nullable()->after('order_num');
            $table->enum('status', WorkManagementStatus::getValues())->default(WorkManagementStatus::DRAFT)->after('public_date');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('work_managements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'public_date', 'status']);
        });
    }
};
