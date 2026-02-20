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
        Schema::create('tdbs_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->nullable()->index('options_shop_id_foreign');
            $table->unsignedBigInteger('schedule_id')->nullable()->index('options_schedule_id_foreign');
            $table->string('name')->nullable();
            $table->bigInteger('price')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->smallInteger('unit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->tinyInteger('user_type')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraints
            $table->foreign('schedule_id')->references('id')->on('tdbs_schedules')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign('shop_id')->references('id')->on('tdbs_shops')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tdbs_options');
    }
};
