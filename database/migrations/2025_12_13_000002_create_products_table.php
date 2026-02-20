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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('商品名');
            $table->text('description')->nullable()->comment('説明');
            $table->unsignedBigInteger('category_id')->nullable()->comment('カテゴリID');
            $table->unsignedBigInteger('payment_trigger_id')->nullable()->comment('決済トリガーID');
            $table->boolean('status')->default(true)->comment('公開ステータス');
            $table->string('image_url')->nullable()->comment('メイン画像URL');
            $table->json('images')->nullable()->comment('複数画像URL配列');
            $table->integer('display_order')->default(0)->comment('表示順序');
            // アクセス設定
            $table->json('access_settings')->nullable()->comment('公開設定 {public, member, admin}');
            // 販売範囲
            $table->json('sales_scopes')->nullable()->comment('販売範囲配列');
            // 会員特典
            $table->json('grant_membership_ids')->nullable()->comment('購入後に付与するメンバーシップID配列');
            // 価格情報（決済トリガーから取得可能だが、商品独自の価格表示用）
            $table->integer('price')->nullable()->comment('表示価格');
            $table->integer('original_price')->nullable()->comment('元価格（割引前）');
            $table->string('price_label')->nullable()->comment('価格ラベル（例: 月額、一括）');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('作成者ID');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('category')->nullOnDelete();
            $table->foreign('payment_trigger_id')->references('id')->on('payment_triggers')->nullOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
