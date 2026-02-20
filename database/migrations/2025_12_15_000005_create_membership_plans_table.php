<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_id');
            $table->string('plan_id', 36)->comment('UUID from plans table');
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade');
            $table->foreign('plan_id')->references('plan_id')->on('plans')->onDelete('cascade');

            $table->unique(['membership_id', 'plan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
