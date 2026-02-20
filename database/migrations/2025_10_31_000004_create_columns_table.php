<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('タイトル');
            $table->string('short_description', 500)->nullable()->comment('短い説明');
            $table->unsignedBigInteger('category_id')->comment('カテゴリーのテープルにid')->nullable();
            $table->text('content')->comment('内容');
            $table->unsignedTinyInteger('status')->nullable()->comment('ステータス');
            $table->unsignedBigInteger('creator')->comment('作成者');
            $table->ulid('stripe_account_id')->nullable();
            $table->string('stripe_product_id')->nullable()->comment('Stripe Product ID from API');
            $table->string('stripe_price_id')->nullable()->comment('Stripe Price ID from API');
            $table->string('image')->nullable()->comment('Image file path');
            $table->decimal('price', 10, 2)->default(0.00)->comment('価格');
            $table->boolean('is_member_only')->default(false)->comment('会員限定');
            $table->unsignedInteger('view_count')->default(0)->comment('閲覧数');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('stripe_account_id')->references('stripe_account_id')->on('stripe_account')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
