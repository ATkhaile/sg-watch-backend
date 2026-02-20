<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\WorkReactionType;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_management_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('reaction', WorkReactionType::getValues());
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('work_management_id')->references('id')->on('work_managements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_reactions');
    }
};
