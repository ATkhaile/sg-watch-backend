<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_management_id');
            $table->string('work_task_url', 255);
            $table->integer('order_num');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('work_management_id')->references('id')->on('work_managements')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_tasks');
    }
};
