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
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_id')->index('reservations_location_id_foreign');
            $table->unsignedBigInteger('account_id')->index('reservations_account_id_foreign');
            $table->unsignedBigInteger('availability_slot_id')->nullable()->index('reservations_availability_slot_id_foreign');
            $table->unsignedBigInteger('user_plan_id')->nullable()->index('reservations_user_plan_id_foreign');
            $table->string('charge_id')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('reservation_number')->nullable();
            $table->date('usage_date');
            $table->time('usage_time_start')->nullable();
            $table->time('usage_time_end')->nullable();
            $table->tinyInteger('usage_type')->nullable()->default(1)->comment('利用タイプ');
            $table->unsignedBigInteger('usage_slot_option_id')->nullable()->index('reservations_usage_slot_option_id_foreign');
            $table->bigInteger('total_amount')->comment('総額');
            $table->tinyInteger('status')->default(1)->comment('予約ステータス');
            $table->unsignedBigInteger('slot_option_id')->nullable()->index('reservations_slot_option_id_foreign');
            $table->bigInteger('slot_option_price')->nullable();
            $table->unsignedBigInteger('slot_option_type1_id')->nullable()->index('reservations_slot_option_type1_id_foreign');
            $table->bigInteger('slot_option_type1_price')->nullable();
            $table->unsignedBigInteger('slot_option_type2_id')->nullable()->index('reservations_slot_option_type2_id_foreign');
            $table->bigInteger('slot_option_type2_price')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable()->index('reservations_coupon_id_foreign')->comment('使用クーポンID(Null許容)');
            $table->bigInteger('discount_amount')->nullable();
            $table->bigInteger('day_trip_normal_price')->nullable()->comment('平日');
            $table->bigInteger('day_trip_holiday_price')->nullable()->comment('土日祝');
            $table->bigInteger('stay_normal_price')->nullable()->comment('平日');
            $table->bigInteger('stay_holiday_price')->nullable()->comment('土日祝');
            $table->boolean('parking_flag')->default(false);
            $table->bigInteger('parking_price')->nullable()->comment('駐車場');
            $table->boolean('instructor_flag')->default(false);
            $table->bigInteger('instructor_price')->nullable();
            $table->boolean('lesson_flag')->default(false);
            $table->bigInteger('lesson_price')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->tinyInteger('user_type')->default(1);
            $table->string('remote_log_id')->nullable();
            $table->string('pin')->nullable();
            $table->string('client_secret')->nullable()->unique();
            $table->dateTime('canceled_at')->nullable();
            $table->tinyInteger('is_extend')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('availability_slot_id')->references('id')->on('availability_slots')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('slot_option_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('slot_option_type1_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('slot_option_type2_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('usage_slot_option_id')->references('id')->on('slot_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('user_plan_id')->references('id')->on('tdbs_user_plans')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
