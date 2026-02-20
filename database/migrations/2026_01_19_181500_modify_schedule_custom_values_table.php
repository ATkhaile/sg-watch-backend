<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('schedule_custom_values', function (Blueprint $table) {
            // labelをnullableに変更
            $table->string('label')->nullable()->change();
            // priceをdecimal(15,2)に変更して浮動小数点対応
            $table->decimal('price', 15, 2)->nullable()->change();
            // valueをtextに変更してより長いテキストを許容
            $table->text('value')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedule_custom_values', function (Blueprint $table) {
            $table->string('label')->change();
            $table->integer('price')->nullable()->change();
            $table->string('value')->nullable()->change();
        });
    }
};
