<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Columns機能をNews相当にアップグレード
     * - News機能の追加（公開日時、重要度、新着、通知）
     * - Stripe決済関連フィールドの削除
     * - 画像フィールド名をthumbnailに変更
     */
    public function up(): void
    {
        Schema::table('columns', function (Blueprint $table) {
            // News機能の追加
            $table->boolean('is_important')->default(false)->after('is_member_only')->comment('重要なコラムフラグ');
            $table->boolean('is_new')->default(false)->after('is_important')->comment('新着フラグ');
            $table->dateTime('published_at')->nullable()->after('is_new')->comment('公開日時');
            $table->boolean('send_notification')->default(false)->after('published_at')->comment('通知送信フラグ');

            // 画像フィールド名を変更
            $table->renameColumn('image', 'thumbnail');
        });

        // Stripe決済関連フィールドを削除
        Schema::table('columns', function (Blueprint $table) {
            // 外部キー制約を先に削除
            $table->dropForeign(['stripe_account_id']);

            // カラムを削除
            $table->dropColumn([
                'stripe_account_id',
                'stripe_product_id',
                'stripe_price_id',
                'price',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Stripe決済関連フィールドを復元（存在しない場合のみ）
        Schema::table('columns', function (Blueprint $table) {
            if (!Schema::hasColumn('columns', 'stripe_account_id')) {
                $table->ulid('stripe_account_id')->nullable()->after('creator');
            }
            if (!Schema::hasColumn('columns', 'stripe_product_id')) {
                $table->string('stripe_product_id')->nullable()->comment('Stripe Product ID from API')->after('stripe_account_id');
            }
            if (!Schema::hasColumn('columns', 'stripe_price_id')) {
                $table->string('stripe_price_id')->nullable()->comment('Stripe Price ID from API')->after('stripe_product_id');
            }
            if (!Schema::hasColumn('columns', 'price')) {
                $table->decimal('price', 10, 2)->default(0.00)->comment('価格')->after('stripe_price_id');
            }
        });

        // 画像フィールド名を戻す（thumbnailが存在してimageが存在しない場合のみ）
        if (Schema::hasColumn('columns', 'thumbnail') && !Schema::hasColumn('columns', 'image')) {
            Schema::table('columns', function (Blueprint $table) {
                $table->renameColumn('thumbnail', 'image');
            });
        }

        // 外部キー制約を復元（stripe_accountテーブルが存在する場合のみ）
        if (Schema::hasTable('stripe_account')) {
            Schema::table('columns', function (Blueprint $table) {
                $table->foreign('stripe_account_id')->references('stripe_account_id')->on('stripe_account')->onDelete('set null');
            });
        }

        // News機能のカラムを削除（存在する場合のみ）
        $columnsToDropNews = [];
        foreach (['is_important', 'is_new', 'published_at', 'send_notification'] as $col) {
            if (Schema::hasColumn('columns', $col)) {
                $columnsToDropNews[] = $col;
            }
        }
        if (!empty($columnsToDropNews)) {
            Schema::table('columns', function (Blueprint $table) use ($columnsToDropNews) {
                $table->dropColumn($columnsToDropNews);
            });
        }
    }
};
