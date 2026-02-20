<?php

use App\Enums\SignatureInfoType;
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
        Schema::create('signature_infos', function (Blueprint $table) {
            $table->id();
            $table->enum('type', SignatureInfoType::getValues());
            $table->string('app_id')->nullable();
            $table->string('domain')->nullable();
            $table->string('secret_key');
            $table->dateTime('expired_at')->nullable();
            $table->boolean('unlimit_expires')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_infos');
    }
};
