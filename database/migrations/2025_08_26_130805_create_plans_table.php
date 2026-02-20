<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tdbs_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->default(1)->index('plans_shop_id_foreign');
            $table->string('name');
            $table->char('code', 36)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('price');
            $table->string('highline_display');
            $table->string('available_reservation');
            $table->time('start_time');
            $table->time('end_time');
            $table->smallInteger('accompanying_slots');
            $table->integer('limit')->nullable();
            $table->boolean('no_limit')->default(false);
            $table->tinyInteger('available_from_type')->default(1);
            $table->bigInteger('available_from_value')->default(1);
            $table->tinyInteger('available_from_unit')->default(1);
            $table->tinyInteger('available_to_type')->default(1);
            $table->bigInteger('available_to_value')->default(1);
            $table->tinyInteger('available_to_unit')->default(1);
            $table->bigInteger('charge_people_price')->default(1000);
            $table->bigInteger('charge_time_price')->default(1000);
            $table->bigInteger('charge_time_price_1')->default(1000);
            $table->bigInteger('charge_time_price_2')->default(1000);
            $table->bigInteger('charge_time_price_3')->default(1000);
            $table->string('stripe_payment_link')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraint
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_plans');
    }
};
