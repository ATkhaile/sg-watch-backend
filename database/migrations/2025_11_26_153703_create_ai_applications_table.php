<?php

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
        Schema::create('ai_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('provider_id')->constrained('ai_providers')->onDelete('cascade');
            $table->text('prompt')->nullable();
            $table->string('role')->default('user');
            $table->string('model');
            $table->string('app_api_key')->nullable();  
            $table->double('temperature')->default(1);
            $table->integer('max_tokens')->nullable();
            $table->boolean('active')->default(true);
            $table->json('extra_params')->nullable();
            $table->softDeletes();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_applications');
    }
};
