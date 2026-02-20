<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 100)->comment('Address name: 自宅, 会社, Nhà, Công ty...');
            $table->enum('country_code', ['JP', 'VN']);
            $table->enum('input_mode', ['manual', 'image_only'])->default('manual');
            $table->string('postal_code', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('image_url', 500)->nullable()->comment('1 image per address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'label']);
            $table->index(['user_id', 'country_code']);
            $table->index(['user_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
