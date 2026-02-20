<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_address_jp', function (Blueprint $table) {
            $table->foreignId('address_id')->primary()->constrained('user_addresses')->cascadeOnDelete();
            $table->string('prefecture', 50)->nullable()->comment('都道府県');
            $table->string('city', 100)->nullable()->comment('市区町村');
            $table->string('ward_town', 100)->nullable()->comment('町域');
            $table->string('banchi', 100)->nullable()->comment('番地');
            $table->string('room_no', 50)->nullable()->comment('部屋番号');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_address_jp');
    }
};
