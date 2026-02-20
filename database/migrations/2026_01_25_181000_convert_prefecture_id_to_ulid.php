<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * tdbs_accounts と tdbs_payment_histories の prefecture_id を
     * 整数型から ULID (string) に変換し、共通 prefectures テーブルを参照するようにする
     */
    public function up(): void
    {
        // 1. 外部キー制約を削除
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->dropForeign(['prefecture_id']);
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropForeign(['billing_prefecture_id']);
        });

        // 2. 新しいULIDカラムを追加
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->string('prefecture_ulid', 26)->nullable()->after('prefecture_id');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->string('billing_prefecture_ulid', 26)->nullable()->after('billing_prefecture_id');
        });

        // 3. データ移行: 都道府県名でマッチングして ULID を設定
        // tdbs_prefectures の name と prefectures の name でマッチング
        DB::statement("
            UPDATE tdbs_accounts ta
            SET ta.prefecture_ulid = (
                SELECT p.prefecture_id
                FROM prefectures p
                INNER JOIN tdbs_prefectures tp ON tp.name = p.name
                WHERE tp.id = ta.prefecture_id
                LIMIT 1
            )
            WHERE ta.prefecture_id IS NOT NULL
        ");

        DB::statement("
            UPDATE tdbs_payment_histories tph
            SET tph.billing_prefecture_ulid = (
                SELECT p.prefecture_id
                FROM prefectures p
                INNER JOIN tdbs_prefectures tp ON tp.name = p.name
                WHERE tp.id = tph.billing_prefecture_id
                LIMIT 1
            )
            WHERE tph.billing_prefecture_id IS NOT NULL
        ");

        // 4. 古いカラムを削除し、新しいカラムをリネーム
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->dropColumn('prefecture_id');
        });

        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->renameColumn('prefecture_ulid', 'prefecture_id');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropColumn('billing_prefecture_id');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->renameColumn('billing_prefecture_ulid', 'billing_prefecture_id');
        });

        // 5. 新しい外部キー制約を追加（共通 prefectures テーブルへ）
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->foreign('prefecture_id')
                ->references('prefecture_id')
                ->on('prefectures')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->foreign('billing_prefecture_id')
                ->references('prefecture_id')
                ->on('prefectures')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });

        // 6. tdbs_prefectures テーブルを削除
        Schema::dropIfExists('tdbs_prefectures');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. tdbs_prefectures テーブルを再作成
        Schema::create('tdbs_prefectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('都道府県名');
            $table->integer('order_num')->comment('表示順');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. 外部キー制約を削除
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->dropForeign(['prefecture_id']);
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropForeign(['billing_prefecture_id']);
        });

        // 3. カラムを整数型に戻す
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('prefecture_id_int')->nullable()->after('prefecture_id');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('billing_prefecture_id_int')->nullable()->after('billing_prefecture_id');
        });

        // 4. 古いカラムを削除し、新しいカラムをリネーム
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->dropColumn('prefecture_id');
        });

        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->renameColumn('prefecture_id_int', 'prefecture_id');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->dropColumn('billing_prefecture_id');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->renameColumn('billing_prefecture_id_int', 'billing_prefecture_id');
        });

        // 5. 外部キー制約を復元
        Schema::table('tdbs_accounts', function (Blueprint $table) {
            $table->foreign('prefecture_id')
                ->references('id')
                ->on('tdbs_prefectures')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });

        Schema::table('tdbs_payment_histories', function (Blueprint $table) {
            $table->foreign('billing_prefecture_id')
                ->references('id')
                ->on('tdbs_prefectures')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }
};
