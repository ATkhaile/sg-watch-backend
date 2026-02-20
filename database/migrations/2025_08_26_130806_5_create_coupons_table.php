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
        Schema::create('tdbs_coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id');
            $table->string('name');
            $table->char('code', 36)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('coupon_type')->default(1);
            $table->integer('discount')->default(0);
            $table->tinyInteger('limit_type')->default(1);
            $table->integer('limit')->nullable();
            $table->tinyInteger('expire_type')->default(1);
            $table->date('expire_start_date')->nullable();
            $table->date('expire_end_date')->nullable();
            $table->tinyInteger('discount_option_type')->default(1);
            $table->tinyInteger('account_use_type')->default(1);
            $table->bigInteger('maximum_account')->nullable();
            $table->json('target_user')->nullable();
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
        Schema::dropIfExists('tdbs_coupons');
    }
};
