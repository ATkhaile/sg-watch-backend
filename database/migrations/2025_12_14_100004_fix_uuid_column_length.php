<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // すべての外部キー制約を削除
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->dropForeign('payment_plans_stripe_account_id_foreign');
        });

        Schema::table('stripe_dashboard_stats', function (Blueprint $table) {
            $table->dropForeign('stripe_dashboard_stats_stripe_account_id_foreign');
        });

        // columnsテーブルの外部キー制約を削除（リネームマイグレーションで再作成されたもの）
        try {
            Schema::table('columns', function (Blueprint $table) {
                $table->dropForeign('columns_stripe_account_id_foreign');
            });
        } catch (\Exception $e) {
            // 外部キーが存在しない場合はスキップ
        }

        // uuidカラムを36文字に拡張（UUIDは36文字必要）
        DB::statement("ALTER TABLE stripe_accounts MODIFY COLUMN uuid VARCHAR(36) NOT NULL");

        // 関連テーブルのstripe_account_idも更新
        DB::statement("ALTER TABLE payment_plans MODIFY COLUMN stripe_account_id VARCHAR(36) NULL");
        DB::statement("ALTER TABLE stripe_dashboard_stats MODIFY COLUMN stripe_account_id VARCHAR(36) NOT NULL");
        DB::statement("ALTER TABLE columns MODIFY COLUMN stripe_account_id VARCHAR(36) NULL");

        // 外部キー制約を再作成
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->foreign('stripe_account_id', 'payment_plans_stripe_account_id_foreign')
                ->references('uuid')
                ->on('stripe_accounts')
                ->onDelete('set null');
        });

        Schema::table('stripe_dashboard_stats', function (Blueprint $table) {
            $table->foreign('stripe_account_id', 'stripe_dashboard_stats_stripe_account_id_foreign')
                ->references('uuid')
                ->on('stripe_accounts')
                ->onDelete('cascade');
        });

        Schema::table('columns', function (Blueprint $table) {
            $table->foreign('stripe_account_id', 'columns_stripe_account_id_foreign')
                ->references('uuid')
                ->on('stripe_accounts')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Note: 36文字のUUID/ULIDデータを保持するため、カラムサイズは縮小しません。
     * ロールバック時も36文字のVARCHARを維持します。
     */
    public function down(): void
    {
        // データ互換性のため、カラムサイズの変更は行わない
        // 外部キー制約はそのまま維持
    }
};
