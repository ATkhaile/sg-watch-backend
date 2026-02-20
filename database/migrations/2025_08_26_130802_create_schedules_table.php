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
        Schema::create('tdbs_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->nullable()->index('schedules_shop_id_foreign');
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('schedule_type')->default(0);
            $table->bigInteger('day_trip_normal_price')->nullable()->comment('平日');
            $table->bigInteger('day_trip_holiday_price')->nullable()->comment('土日祝');
            $table->bigInteger('stay_normal_price')->nullable()->comment('平日');
            $table->bigInteger('stay_holiday_price')->nullable()->comment('土日祝');
            $table->tinyInteger('parking_flag')->default(0);
            $table->bigInteger('parking_price')->nullable()->comment('駐車場');
            $table->tinyInteger('instructor_flag')->default(0);
            $table->bigInteger('instructor_price')->nullable();
            $table->tinyInteger('lesson_flag')->default(0);
            $table->bigInteger('lesson_price')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_schedules');
    }
};
