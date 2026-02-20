<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PointMasterType;
use App\Enums\PointMasterStatus;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_masters', function (Blueprint $table) {
            $table->id();
            $table->enum('type', PointMasterType::getValues());
            $table->boolean('status')->default(PointMasterStatus::ON);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('point')->nullable();
            $table->bigInteger('inviter_point')->nullable();
            $table->bigInteger('invitee_point')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_masters');
    }
};
