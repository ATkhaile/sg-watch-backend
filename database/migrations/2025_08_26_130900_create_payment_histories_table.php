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
        Schema::create('tdbs_payment_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->index('payment_histories_shop_id_foreign');
            $table->unsignedBigInteger('account_id')->index('payment_histories_account_id_foreign');
            $table->unsignedBigInteger('reservation_id')->index('payment_histories_reservation_id_foreign');
            $table->unsignedBigInteger('card_id')->nullable()->index('payment_histories_card_id_foreign');
            $table->unsignedBigInteger('schedule_id')->nullable()->index('payment_histories_schedule_id_foreign');
            $table->string('payment_intent_id')->nullable();
            $table->tinyInteger('usage_type')->nullable()->default(1)->comment('利用タイプ');
            $table->bigInteger('total_amount')->comment('総額');
            $table->tinyInteger('payment_status')->default(1)->comment('支払いステータス');
            $table->string('card_holder_name')->nullable();
            $table->string('billing_postal_code', 8)->nullable();
            $table->unsignedBigInteger('billing_prefecture_id')->nullable()->index('payment_histories_billing_prefecture_id_foreign');
            $table->string('billing_city')->nullable();
            $table->string('billing_street_address')->nullable();
            $table->string('billing_building')->nullable();
            $table->string('billing_tel')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraints
            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('billing_prefecture_id')->references('id')->on('tdbs_prefectures')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('card_id')->references('id')->on('tdbs_cards')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('reservation_id')->references('id')->on('tdbs_reservations')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_payment_histories');
    }
};
