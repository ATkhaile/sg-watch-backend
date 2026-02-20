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
        Schema::create('tdbs_shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('shop_type')->nullable()->default(1);
            $table->string('name');
            $table->text('description')->nullable()->comment('店舗説明');
            $table->text('images')->nullable();
            $table->string('image_top')->nullable();
            $table->string('image_nav')->nullable();
            $table->string('line_id')->nullable();
            $table->bigInteger('day_trip_normal_price')->nullable()->comment('平日');
            $table->bigInteger('day_trip_holiday_price')->nullable()->comment('土日祝');
            $table->bigInteger('stay_normal_price')->nullable()->comment('平日');
            $table->bigInteger('stay_holiday_price')->nullable()->comment('土日祝');
            $table->tinyInteger('parking_flag')->default(0);
            $table->bigInteger('parking_price')->nullable()->comment('駐車場');
            $table->tinyInteger('instructor_flag_1')->default(0);
            $table->bigInteger('instructor_price_1')->nullable();
            $table->tinyInteger('lesson_flag_1')->default(0);
            $table->bigInteger('lesson_price_1')->nullable();
            $table->tinyInteger('instructor_flag_2')->nullable()->default(0);
            $table->bigInteger('instructor_price_2')->nullable();
            $table->tinyInteger('lesson_flag_2')->nullable()->default(0);
            $table->bigInteger('lesson_price_2')->nullable();
            $table->tinyInteger('instructor_flag_3')->nullable()->default(1);
            $table->bigInteger('instructor_price_3')->nullable()->default(0);
            $table->tinyInteger('lesson_flag_3')->nullable()->default(0);
            $table->bigInteger('lesson_price_3')->nullable();
            $table->string('map', 1000)->nullable();
            $table->string('map_link')->nullable();
            $table->string('address')->nullable();
            $table->boolean('visiter_flag_book')->default(true);
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->tinyInteger('lesson_setting_type')->default(2);
            $table->bigInteger('lesson_setting_value')->default(1);
            $table->tinyInteger('lesson_setting_unit')->default(3);
            $table->tinyInteger('instructor_setting_type')->default(2);
            $table->bigInteger('instructor_setting_value')->default(1);
            $table->tinyInteger('instructor_setting_unit')->default(3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_shops');
    }
};
