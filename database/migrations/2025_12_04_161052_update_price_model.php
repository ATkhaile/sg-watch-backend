<?php

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
        Schema::table('ai_app_messages', function (Blueprint $table) {
            if (Schema::hasColumn('ai_app_messages', 'message_price_unit')) {
                $table->dropColumn('message_price_unit');
            }
            if (Schema::hasColumn('ai_app_messages', 'answer_price_unit')) {
                $table->dropColumn('answer_price_unit');
            }
            if (Schema::hasColumn('ai_app_messages', 'usd_to_jpy')) {
                $table->dropColumn('usd_to_jpy');
            }

            $table->decimal('message_price_unit', 18, 10)->nullable();
            $table->decimal('answer_price_unit', 18, 10)->nullable();
            $table->decimal('usd_to_jpy', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->dropColumn('message_price_unit');
            $table->dropColumn('answer_price_unit');
            $table->dropColumn('usd_to_jpy');
        });
    }
};
