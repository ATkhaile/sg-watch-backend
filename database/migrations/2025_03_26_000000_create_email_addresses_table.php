<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->comment('ユーザーのテープルにuser_id');
            $table->string('email', 270)->unique()->comment('メールアドレス');
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_addresses');
    }
};
