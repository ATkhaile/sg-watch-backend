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
        Schema::table('products', function (Blueprint $table) {
            // 不要なカラムを削除
            $table->dropColumn(['price', 'original_price', 'price_label']);

            // 新しいカラムを追加
            $table->string('product_code')->nullable()->after('name')->comment('商品コード');
            $table->string('plan_text')->nullable()->after('product_code')->comment('価格（自由入力）');
            $table->string('original_plan_text')->nullable()->after('plan_text')->comment('元価格（自由入力）');
            $table->text('purchased_content')->nullable()->after('description')->comment('購入後に確認できるコンテンツ');
            $table->boolean('is_active')->default(true)->after('status')->comment('アクティブ状態');
            $table->json('tags')->nullable()->after('images')->comment('タグ配列');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 追加したカラムを削除
            $table->dropColumn(['product_code', 'plan_text', 'original_plan_text', 'purchased_content', 'is_active', 'tags']);

            // 削除したカラムを復元
            $table->integer('price')->nullable()->after('grant_membership_ids')->comment('表示価格');
            $table->integer('original_price')->nullable()->after('price')->comment('元価格（割引前）');
            $table->string('price_label')->nullable()->after('original_price')->comment('価格ラベル（例: 月額、一括）');
        });
    }
};
