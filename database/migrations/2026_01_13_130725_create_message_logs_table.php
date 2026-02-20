<?php

use App\Enums\MessageSenderType;
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
        Schema::create('message_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('sender_type', MessageSenderType::getValues())
                ->default(MessageSenderType::SCENARIO);
            $table->longText('content')->nullable();

            $table->unsignedBigInteger('media_id')->nullable();
            $table->unsignedBigInteger('scenario_step_id')->nullable();

            $table->dateTime('sent_at')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('media_id')
                ->references('id')->on('media_uploads')
                ->nullOnDelete();

            $table->foreign('scenario_step_id')
                ->references('id')->on('scenario_steps')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_logs');
    }
};
