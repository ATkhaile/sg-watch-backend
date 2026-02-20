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
        // columnsテーブルの外部キー制約を削除（リネーム前に必要）
        if (Schema::hasTable('columns')) {
            Schema::table('columns', function (Blueprint $table) {
                try {
                    $table->dropForeign('columns_stripe_account_id_foreign');
                } catch (\Exception $e) {
                    // 外部キーが存在しない場合はスキップ
                }
            });
        }

        Schema::rename('stripe_account', 'stripe_accounts');

        // columnsテーブルの外部キー制約を再作成（新しいテーブル名で）
        if (Schema::hasTable('columns')) {
            Schema::table('columns', function (Blueprint $table) {
                $table->foreign('stripe_account_id', 'columns_stripe_account_id_foreign')
                    ->references('stripe_account_id')
                    ->on('stripe_accounts')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // columnsテーブルの外部キー制約を削除
        if (Schema::hasTable('columns')) {
            Schema::table('columns', function (Blueprint $table) {
                try {
                    $table->dropForeign('columns_stripe_account_id_foreign');
                } catch (\Exception $e) {
                    // 外部キーが存在しない場合はスキップ
                }
            });
        }

        Schema::rename('stripe_accounts', 'stripe_account');

        // columnsテーブルの外部キー制約を再作成（元のテーブル名で）
        if (Schema::hasTable('columns')) {
            Schema::table('columns', function (Blueprint $table) {
                $table->foreign('stripe_account_id', 'columns_stripe_account_id_foreign')
                    ->references('stripe_account_id')
                    ->on('stripe_account')
                    ->onDelete('set null');
            });
        }
    }
};
