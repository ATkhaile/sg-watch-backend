<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_required_memberships', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id', 36)->comment('UUID from plans table');
            $table->unsignedBigInteger('membership_id');
            $table->timestamps();

            $table->foreign('plan_id')->references('plan_id')->on('plans')->onDelete('cascade');
            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade');

            $table->unique(['plan_id', 'membership_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_required_memberships');
    }
};
