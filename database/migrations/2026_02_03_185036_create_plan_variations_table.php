<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_variations', function (Blueprint $table) {
            $table->id();
            $table->char('plan_id', 26);
            $table->string('name');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('limit')->nullable();
            $table->integer('price')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_id')->references('plan_id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_variations');
    }
};
