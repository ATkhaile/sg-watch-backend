<?php

use App\Enums\ClientType;
use App\Enums\SsoProviderType;
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
        Schema::create('sso_providers', function (Blueprint $table) {
            $table->id();
            $table->enum('provider_type', SsoProviderType::getValues());
            $table->enum('client_type', ClientType::getValues());
            $table->string('client_key');
            $table->string('client_secret');
            $table->text('scopes')->nullable();
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
        Schema::dropIfExists('sso_providers');
    }
};
