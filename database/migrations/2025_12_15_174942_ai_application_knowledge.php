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
        Schema::create('ai_application_knowledge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('knowledge_id');
            $table->timestamps();

            $table->foreign('application_id')
                ->references('id')
                ->on('ai_applications')
                ->onDelete('cascade');

            $table->foreign('knowledge_id')
                ->references('id')
                ->on('ai_knowledge')
                ->onDelete('cascade');

            $table->unique(['application_id', 'knowledge_id']);
        });
        Schema::table('ai_applications', function (Blueprint $table) {
            $table->string('type')->default('chatbot');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_application_knowledge');
        Schema::table('ai_applications', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
