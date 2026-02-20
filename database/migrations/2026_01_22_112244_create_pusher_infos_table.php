<?php

use App\Enums\PushType;
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
        Schema::create('pusher_infos', function (Blueprint $table) {
            $table->id();

            $table->enum('push_type', [PushType::PUSHER,PushType::FIREBASE,]);

            $table->string('firebase_project_id')->nullable();
            $table->string('firebase_credential_name')->nullable();
            $table->string('firebase_credentials_path')->nullable();

            $table->string('pusher_app_id')->nullable();
            $table->string('pusher_app_key')->nullable();
            $table->string('pusher_app_secret')->nullable();
            $table->string('pusher_app_cluster')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pusher_infos');
    }
};
