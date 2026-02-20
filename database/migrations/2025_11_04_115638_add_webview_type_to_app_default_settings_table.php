<?php


use App\Enums\WebviewType;
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
        Schema::table('app_default_settings', function (Blueprint $table) {
            $table->enum('webview_type', WebviewType::getValues())->nullable()->after('footer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_default_settings', function (Blueprint $table) {
            $table->dropColumn('webview_type');
        });
    }
};
