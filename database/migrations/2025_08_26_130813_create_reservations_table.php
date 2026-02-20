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
        Schema::create('tdbs_reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->index('reservations_shop_id_foreign');
            $table->unsignedBigInteger('account_id')->index('reservations_account_id_foreign');
            $table->unsignedBigInteger('schedule_id')->nullable()->index('reservations_schedule_id_foreign');
            $table->unsignedBigInteger('user_plan_id')->nullable()->index('reservations_user_plan_id_foreign');
            $table->string('charge_id')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('reservation_number')->nullable();
            $table->date('usage_date');
            $table->time('usage_time_start')->nullable();
            $table->time('usage_time_end')->nullable();
            $table->tinyInteger('usage_type')->nullable()->default(1)->comment('利用タイプ');
            $table->unsignedBigInteger('usage_option_id')->nullable()->index('reservations_usage_option_id_foreign');
            $table->bigInteger('total_amount')->comment('総額');
            $table->tinyInteger('status')->default(1)->comment('予約ステータス');
            $table->unsignedBigInteger('option_id')->nullable()->index('reservations_option_id_foreign')->comment('パーキング必要性');
            $table->bigInteger('option_price')->nullable();
            $table->unsignedBigInteger('option_type1_id')->nullable()->index('reservations_option_type1_id_foreign');
            $table->bigInteger('option_type1_price')->nullable();
            $table->unsignedBigInteger('option_type2_id')->nullable()->index('reservations_option_type2_id_foreign');
            $table->bigInteger('option_type2_price')->nullable();
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

            $table->foreign('account_id')->references('id')->on('tdbs_accounts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('coupon_id')->references('id')->on('tdbs_coupons')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('option_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('option_type1_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('option_type2_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('usage_option_id')->references('id')->on('tdbs_options')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('user_plan_id')->references('id')->on('tdbs_user_plans')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_reservations');
    }
};
