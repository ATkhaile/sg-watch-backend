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
        Schema::create('ai_model_prices', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->string('provider_name');
            $table->string('price_currency', 10)->default('USD');
            $table->decimal('input_price', 16, 8)->nullable();
            $table->decimal('output_price', 16, 8)->nullable();
            $table->integer('tokens');
            $table->dateTime('started_at');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['model_name', 'provider_name', 'started_at'], 'model_provider_started_unique');
        });
        Schema::table('ai_app_messages', function (Blueprint $table) {
            $table->string('price_currency', 10)->default('USD');
            $table->decimal('message_price_unit')->nullable();
            $table->decimal('answer_price_unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_model_prices');
    }
};
