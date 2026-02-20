<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PointMasterType;
use App\Enums\PointMasterStatus;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_point_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', PointMasterType::getValues());
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('point');
            $table->boolean('status')->default(PointMasterStatus::ON);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_point_settings');
    }
};
