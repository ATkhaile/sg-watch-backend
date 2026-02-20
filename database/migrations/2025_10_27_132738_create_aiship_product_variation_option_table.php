<?php

use App\Enums\Aiship\HandlingStatus;
use App\Enums\Aiship\OptionRateType;
use App\Enums\Aiship\OptionType;
use App\Enums\Aiship\TaxCategory;
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
        Schema::create('aiship_product_variation_option', function (Blueprint $table) {
            $table->id();
            $table->string('control_flag', 50)->nullable();
            $table->string('product_id', 50);
            $table->enum('option_type', OptionType::getValues());
            $table->string('option_name', 100)->nullable();
            $table->string('option_choice_name', 100)->nullable();
            $table->text('option_include_setting')->nullable();
            $table->text('option_exclude_setting')->nullable();
            $table->text('option_description')->nullable();
            $table->decimal('option_rate', 8, 2)->nullable();
            $table->enum('option_rate_type', OptionRateType::getValues())->nullable();

            $table->string('variation1_name', 100)->nullable();
            $table->string('variation2_name', 100)->nullable();
            $table->string('variation1_choice_no', 50)->nullable();
            $table->string('variation2_choice_no', 50)->nullable();
            $table->string('variation1_choice_name', 100)->nullable();
            $table->string('variation2_choice_name', 100)->nullable();
            $table->integer('variation_stock')->nullable();

            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('dark_market_price', 10, 2)->nullable();
            $table->string('product_code', 50)->nullable();
            $table->enum('option_tax_type', TaxCategory::getValues())->nullable();
            $table->string('jan_code', 13)->nullable();

            $table->boolean('variation1_color_size_flag')->nullable();
            $table->boolean('variation2_color_size_flag')->nullable();
            $table->enum('handling_status', HandlingStatus::getValues())->nullable();
            $table->foreign('product_id')->references('product_id')->on('aiship_product_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aiship_product_variation_option');
    }
};
