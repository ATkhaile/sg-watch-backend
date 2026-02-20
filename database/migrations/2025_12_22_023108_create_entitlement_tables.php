<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Entitlement（エンタイトルメント）ドメイン用テーブルを作成
     *
     * EntitlementはRole/Permissionとは異なり、アプリケーション機能へのアクセスを
     * Membership & User単位で制御する仕組み
     *
     * 3つのタイプ:
     * - ON_OFF: 機能の有効/無効（例: paywall_disabled, ads_free）
     * - QUOTA: 使用上限（リセット可能、例: ai_chat_monthly_limit）
     * - CONSUMABLE: 消費型（例: ai_tokens, download_credits）
     */
    public function up(): void
    {
        // ============================================================
        // entitlement_types - エンタイトルメントタイプ（マスター）
        // ============================================================
        Schema::create('entitlement_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique()->comment('一意の識別子（例: paywall_disabled）');
            $table->string('name', 255)->comment('表示名（日本語）');
            $table->text('description')->nullable()->comment('説明');
            $table->enum('type', ['on_off', 'quota', 'consumable'])->comment('タイプ: on_off, quota, consumable');
            $table->string('default_value', 255)->nullable()->comment('デフォルト値（on_off: true/false, quota/consumable: 数値）');
            $table->json('metadata')->nullable()->comment('追加設定（reset_period, unit等）');
            $table->string('category', 100)->nullable()->comment('カテゴリ分類');
            $table->integer('display_order')->default(0)->comment('表示順');
            $table->boolean('is_active')->default(true)->comment('有効/無効');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'display_order']);
            $table->index('is_active');
        });

        // ============================================================
        // membership_entitlements - Membershipレベルのエンタイトルメント設定
        // ============================================================
        Schema::create('membership_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('memberships')->cascadeOnDelete();
            $table->foreignId('entitlement_type_id')->constrained('entitlement_types')->cascadeOnDelete();
            $table->string('value', 255)->comment('付与値（on_off: true/false, quota/consumable: 数値）');
            $table->boolean('is_enabled')->default(true)->comment('このMembershipでこのEntitlementを付与するか');
            $table->timestamps();

            $table->unique(['membership_id', 'entitlement_type_id'], 'membership_entitlement_unique');
        });

        // ============================================================
        // user_entitlements - ユーザー単位のエンタイトルメント（実際の付与状態）
        // ============================================================
        Schema::create('user_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('entitlement_type_id')->constrained('entitlement_types')->cascadeOnDelete();
            $table->foreignId('membership_id')->nullable()->constrained('memberships')->nullOnDelete()
                  ->comment('どのMembership経由で付与されたか（NULLの場合は直接付与）');
            $table->string('value', 255)->comment('現在の値（consumableの場合は残量）');
            $table->string('granted_value', 255)->nullable()->comment('Membership経由で付与された元の値');
            $table->string('override_value', 255)->nullable()->comment('管理者によるオーバーライド値');
            $table->boolean('is_overridden')->default(false)->comment('オーバーライド中かどうか');
            $table->enum('source', ['membership', 'direct', 'purchase', 'promotion'])->default('membership')
                  ->comment('付与元: membership, direct（直接付与）, purchase（購入）, promotion（プロモーション）');
            $table->timestamp('last_reset_at')->nullable()->comment('Quotaの最終リセット日時');
            $table->timestamp('expires_at')->nullable()->comment('有効期限（Membershipと連動）');
            $table->foreignId('granted_by_id')->nullable()->constrained('users')->nullOnDelete()
                  ->comment('付与した管理者のユーザーID');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'entitlement_type_id']);
            $table->index('expires_at');
            $table->index('source');
        });

        // ============================================================
        // user_entitlement_logs - エンタイトルメント消費・付与ログ
        // ============================================================
        Schema::create('user_entitlement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('entitlement_type_id')->constrained('entitlement_types')->cascadeOnDelete();
            $table->foreignId('user_entitlement_id')->nullable()->constrained('user_entitlements')->nullOnDelete();
            $table->enum('action', ['grant', 'consume', 'reset', 'override', 'expire', 'revoke'])
                  ->comment('アクション: grant（付与）, consume（消費）, reset（リセット）, override（オーバーライド）, expire（期限切れ）, revoke（剥奪）');
            $table->string('previous_value', 255)->nullable()->comment('変更前の値');
            $table->string('new_value', 255)->nullable()->comment('変更後の値');
            $table->string('change_amount', 255)->nullable()->comment('変更量（+/-）');
            $table->text('reason')->nullable()->comment('理由・メモ');
            $table->json('metadata')->nullable()->comment('追加データ');
            $table->foreignId('performed_by_id')->nullable()->constrained('users')->nullOnDelete()
                  ->comment('操作した管理者のユーザーID');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['entitlement_type_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_entitlement_logs');
        Schema::dropIfExists('user_entitlements');
        Schema::dropIfExists('membership_entitlements');
        Schema::dropIfExists('entitlement_types');
    }
};
