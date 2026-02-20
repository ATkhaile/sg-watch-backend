<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ユーザー認証情報テーブル
     * 複数の認証プロバイダー（email, LINE, Google, Apple等）をサポート
     */
    public function up(): void
    {
        Schema::create('user_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 50)->comment('認証プロバイダー: email, line, google, apple');
            $table->string('provider_id')->nullable()->comment('プロバイダー側のユーザーID');
            $table->string('email', 270)->nullable()->comment('認証用メールアドレス');
            $table->string('password')->nullable()->comment('パスワード（email認証用）');
            $table->text('access_token')->nullable()->comment('OAuthアクセストークン');
            $table->text('refresh_token')->nullable()->comment('OAuthリフレッシュトークン');
            $table->timestamp('token_expires_at')->nullable()->comment('トークン有効期限');
            $table->boolean('is_primary')->default(false)->comment('メイン認証方式フラグ');
            $table->boolean('verified')->default(false)->comment('認証確認済みフラグ');
            $table->timestamps();

            // インデックス
            $table->unique(['provider', 'provider_id'], 'user_credentials_provider_id_unique');
            $table->unique(['provider', 'email'], 'user_credentials_provider_email_unique');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_credentials');
    }
};
