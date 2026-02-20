<?php

use App\Enums\RedirectType;
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
        Schema::table('notification_pushs', function (Blueprint $table) {
            $table->enum('redirect_type', RedirectType::getValues())->nullable()->after('sound');
            $table->unsignedBigInteger('app_page_id')->nullable()->after('redirect_type');
            $table->string('attach_file')->nullable()->after('app_page_id');
            $table->string('attach_link')->nullable()->after('attach_file');
            $table->foreign('app_page_id')->references('id')->on('app_pages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_pushs', function (Blueprint $table) {
            $table->dropForeign(['app_page_id']);
            $table->dropColumn(['redirect_type', 'app_page_id', 'attach_file', 'attach_link']);
        });
    }
};
