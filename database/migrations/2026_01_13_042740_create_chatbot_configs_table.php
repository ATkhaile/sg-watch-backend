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
        Schema::create('chatbot_configs', function (Blueprint $table) {
            $table->id();
            $table->string('chatbot_id')->unique();

            // アバターアイコン（最大100枚）- JSON配列でURLを保存
            $table->json('avatar_icons')->nullable();

            // つぶやき文章（最大10,000文章）- 改行区切りのテキスト
            $table->longText('murmurs')->nullable();

            // フローティングボタンの不透明度設定
            $table->unsignedTinyInteger('floating_opacity_active')->default(100);
            $table->unsignedTinyInteger('floating_opacity_idle')->default(30);
            $table->unsignedSmallInteger('floating_opacity_idle_timeout')->default(5);

            // チャットウィンドウの不透明度設定
            $table->unsignedTinyInteger('chat_opacity_active')->default(100);
            $table->unsignedTinyInteger('chat_opacity_idle')->default(30);
            $table->unsignedSmallInteger('chat_opacity_idle_timeout')->default(5);

            // アバター切り替え間隔（秒）
            $table->unsignedSmallInteger('avatar_interval_base')->default(60);
            $table->unsignedSmallInteger('avatar_interval_variance')->default(10);

            // つぶやき表示間隔（秒）
            $table->unsignedSmallInteger('murmur_interval_base')->default(20);
            $table->unsignedSmallInteger('murmur_interval_variance')->default(10);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_configs');
    }
};
