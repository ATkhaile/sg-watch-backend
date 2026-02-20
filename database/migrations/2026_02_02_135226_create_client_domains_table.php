<?php

use App\Enums\ClientType;
use App\Enums\Status;
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
        Schema::create('client_domains', function (Blueprint $table) {
            $table->id();
            $table->enum('client_type', ClientType::getValues());
            $table->string('domain');
            $table->enum('status', Status::getValues())->default(Status::ACTIVE);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_domains');
    }
};
