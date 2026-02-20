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
        Schema::create('currency_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 10);
            $table->string('to_currency', 10);
            $table->decimal('rate', 20, 10);
            $table->date('rate_date');
            $table->string('source', 100)->nullable()->comment('API source name');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['from_currency', 'to_currency', 'rate_date'], 'currency_pair_date_unique');
            $table->index(['from_currency', 'to_currency'], 'currency_pair_index');
            $table->index('rate_date', 'rate_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_exchange_rates');
    }
};
