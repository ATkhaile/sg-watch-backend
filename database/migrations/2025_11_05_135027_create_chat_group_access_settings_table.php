<?php

use App\Enums\GroupAccessSetting;
use App\Enums\GroupRoleType;
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
        Schema::create('chat_group_access_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->enum('access_type', GroupAccessSetting::getValues());
            $table->enum('role_type', GroupRoleType::getValues());
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('group_id')->references('id')->on('chat_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_group_access_settings');
    }
};
