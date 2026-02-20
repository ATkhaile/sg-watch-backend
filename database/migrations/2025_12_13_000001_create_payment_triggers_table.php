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
        Schema::create('payment_triggers', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_account_id')->nullable()->comment('Stripeアカウント参照');
            $table->string('stripe_payment_link_id')->nullable()->comment('Stripe Payment Link ID');
            $table->string('stripe_payment_link_url')->nullable()->comment('Stripe Payment Link URL');
            $table->boolean('has_recurring')->default(false)->comment('定期決済の有無');
            $table->boolean('has_one_time')->default(false)->comment('1回きり決済の有無');
            $table->json('pricing_info')->nullable()->comment('価格情報');
            $table->integer('total_amount')->nullable()->comment('合計金額');
            $table->string('currency', 10)->default('jpy')->comment('通貨');
            $table->timestamp('stripe_last_synced_at')->nullable()->comment('Stripe最終同期日時');
            $table->timestamp('stripe_updated_at')->nullable()->comment('Stripe更新日時');
            $table->boolean('allow_promotion_codes')->default(false)->comment('プロモーションコード許可');
            $table->string('billing_address_collection')->nullable()->comment('請求先住所収集設定');
            $table->string('after_completion_type')->nullable()->comment('完了後の遷移タイプ');
            $table->string('redirect_url')->nullable()->comment('リダイレクトURL');
            // ユーザー編集可能フィールド
            $table->string('title')->nullable()->comment('タイトル');
            $table->text('description')->nullable()->comment('説明');
            $table->boolean('is_active')->default(true)->comment('アクティブ状態');
            $table->string('icon_url')->nullable()->comment('アイコンURL');
            $table->unsignedBigInteger('category_id')->nullable()->comment('カテゴリID');
            $table->json('tags')->nullable()->comment('タグ配列');
            $table->timestamp('user_updated_at')->nullable()->comment('ユーザーによる更新日時');
            $table->boolean('is_modified')->default(false)->comment('ユーザーによる修正フラグ');
            $table->integer('display_order')->default(0)->comment('表示順序');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('category')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_triggers');
    }
};
