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
        Schema::create('scenario_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scenario_id');
            $table->integer('step_order');
            $table->string('label');
            $table->integer('delay_type')->nullable();
            $table->integer('delay_sec')->nullable();
            $table->integer('delay_days')->nullable();
            $table->time('delay_time')->nullable();
            $table->longText('messages')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('scenario_id')->references('id')->on('scenarios')->onDelete('cascade');
            $table->index(['scenario_id', 'step_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenario_steps');
    }
};
