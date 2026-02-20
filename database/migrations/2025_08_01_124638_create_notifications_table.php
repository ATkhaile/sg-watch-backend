<?php

use App\Enums\PushType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->enum('push_type', PushType::getValues());
            $table->dateTime('push_datetime')->nullable();
            $table->boolean(('push_now_flag'))->default(false);
            $table->foreignId('create_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
