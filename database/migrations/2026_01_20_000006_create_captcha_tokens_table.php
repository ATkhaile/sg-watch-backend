<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('captcha_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique()->comment('トークン');
            $table->string('challenge', 10)->comment('チャレンジ文字列（ユーザーが入力する文字）');
            $table->string('ip_address', 45)->nullable()->comment('発行時IPアドレス');
            $table->timestamp('expires_at')->comment('有効期限');
            $table->boolean('used')->default(false)->comment('使用済みフラグ');
            $table->timestamps();

            $table->index(['token', 'used']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('captcha_tokens');
    }
};
