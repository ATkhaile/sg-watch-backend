<?php

use App\Enums\AppReleaseMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_releases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('mode', AppReleaseMode::getValues());
            $table->boolean('required_update_flag')->default(false);
            $table->string('app_store_link')->nullable();
            $table->string('chplay_store_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_releases');
    }
};
