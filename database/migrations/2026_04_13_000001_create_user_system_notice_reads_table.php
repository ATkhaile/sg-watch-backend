<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_system_notice_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('notice_id')->constrained('notices')->onDelete('cascade');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'notice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_system_notice_reads');
    }
};
