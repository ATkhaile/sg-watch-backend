<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('tdbs_contacts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tdbs_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('last_name_kanji')->nullable();
            $table->string('first_name_kanji')->nullable();
            $table->string('last_name_kana')->nullable();
            $table->string('first_name_kana')->nullable();
            $table->tinyInteger('contact_type')->default(1);
            $table->date('birthday')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
