<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->comment('設定種別: global, contact, error_report等');
            $table->string('key', 100)->comment('設定キー: from_email, admin_emails, auto_reply_template等');
            $table->json('value')->comment('設定値（JSON）');
            $table->boolean('enabled')->default(true)->comment('有効/無効');
            $table->text('description')->nullable()->comment('設定の説明');
            $table->timestamps();

            $table->unique(['type', 'key']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_settings');
    }
};
