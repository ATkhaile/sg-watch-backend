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
        Schema::create('user_purchased_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ユーザーID');
            $table->char('plan_id', 26)->comment('プランID (ULID)');
            $table->enum('membership_action', ['none', 'membership_only', 'full'])->default('none')
                ->comment('メンバーシップ付与アクション: none=プランのみ, membership_only=メンバーシップまで, full=ロール/権限まで');
            $table->timestamp('purchased_at')->nullable()->comment('購入日時');
            $table->timestamp('expires_at')->nullable()->comment('有効期限');
            $table->json('metadata')->nullable()->comment('追加情報');
            $table->unsignedBigInteger('granted_by')->nullable()->comment('付与した管理者ID');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('plan_id')->on('plans')->onDelete('cascade');
            $table->foreign('granted_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['user_id', 'plan_id']);
            $table->index('purchased_at');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_purchased_plans');
    }
};
