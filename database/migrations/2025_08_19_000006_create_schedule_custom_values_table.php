<?php

use App\Enums\ScheduleCustomType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedule_custom_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->enum('type', ScheduleCustomType::getValues())->nullable();
            $table->dateTime('datetime')->nullable();
            $table->integer('price')->nullable();
            $table->string('value')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_custom_values');
    }
};
