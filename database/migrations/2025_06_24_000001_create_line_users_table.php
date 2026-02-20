<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('line_users', function (Blueprint $table) {
            $table->id();
            $table->string('channel_id');
            $table->string('line_user_id')->unique();
            $table->string('display_name')->nullable();
            $table->text('avatar_url')->nullable();
            $table->text('status_message')->nullable();
            $table->dateTime('unfollowed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('line_users');
    }
};
