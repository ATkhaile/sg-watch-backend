<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AppSettingType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_default_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_show_name');
            $table->string('app_show_name_jp')->nullable();
            $table->string('app_show_name_other')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('type', AppSettingType::getValues());
            $table->string('name');
            $table->string('icon');
            $table->string('link');
            $table->integer('order_num')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_default_settings');
    }
};
