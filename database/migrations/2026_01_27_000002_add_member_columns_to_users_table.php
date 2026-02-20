<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * usersテーブルにMember統合用のカラムを追加
     * typeカラムでadmin/memberを区別
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ユーザータイプ（admin: 管理者, member: サービス会員）
            if (!Schema::hasColumn('users', 'type')) {
                $table->string('type', 20)->default('admin')->after('uuid')->comment('ユーザータイプ: admin, member');
            }

            // Member用プロフィールカラム
            if (!Schema::hasColumn('users', 'last_name_kanji')) {
                $table->string('last_name_kanji')->nullable()->after('name')->comment('姓（漢字）');
            }
            if (!Schema::hasColumn('users', 'first_name_kanji')) {
                $table->string('first_name_kanji')->nullable()->after('last_name_kanji')->comment('名（漢字）');
            }
            if (!Schema::hasColumn('users', 'last_name_kana')) {
                $table->string('last_name_kana')->nullable()->after('first_name_kanji')->comment('姓（カナ）');
            }
            if (!Schema::hasColumn('users', 'first_name_kana')) {
                $table->string('first_name_kana')->nullable()->after('last_name_kana')->comment('名（カナ）');
            }
            if (!Schema::hasColumn('users', 'line_name')) {
                $table->string('line_name')->nullable()->after('first_name_kana')->comment('LINE表示名');
            }
            if (!Schema::hasColumn('users', 'group_name')) {
                $table->string('group_name')->nullable()->after('line_name')->comment('グループ名');
            }
            if (!Schema::hasColumn('users', 'group_name_kana')) {
                $table->string('group_name_kana')->nullable()->after('group_name')->comment('グループ名（カナ）');
            }

            // 住所情報（既存のaddressとは別にmember用の詳細住所）
            if (!Schema::hasColumn('users', 'postal_code')) {
                $table->string('postal_code', 10)->nullable()->after('city')->comment('郵便番号');
            }
            if (!Schema::hasColumn('users', 'street_address')) {
                $table->string('street_address')->nullable()->after('address')->comment('番地');
            }
            if (!Schema::hasColumn('users', 'building')) {
                $table->string('building')->nullable()->after('street_address')->comment('建物名');
            }

            // 個人情報
            if (!Schema::hasColumn('users', 'birthday')) {
                $table->date('birthday')->nullable()->comment('生年月日');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->comment('電話番号');
            }
            if (!Schema::hasColumn('users', 'email_send')) {
                $table->string('email_send', 270)->nullable()->comment('通知用メールアドレス');
            }

            // Member用ビジネスカラム
            if (!Schema::hasColumn('users', 'account_type')) {
                $table->tinyInteger('account_type')->nullable()->comment('アカウントタイプ');
            }
            if (!Schema::hasColumn('users', 'account_number')) {
                $table->integer('account_number')->nullable()->comment('会員番号');
            }
            if (!Schema::hasColumn('users', 'customer_id')) {
                $table->string('customer_id')->nullable()->comment('Stripe顧客ID');
            }
            if (!Schema::hasColumn('users', 'is_black')) {
                $table->boolean('is_black')->default(false)->comment('ブラックリストフラグ');
            }
            if (!Schema::hasColumn('users', 'memo')) {
                $table->text('memo')->nullable()->comment('管理者メモ');
            }
            if (!Schema::hasColumn('users', 'billing_same_address_flag')) {
                $table->boolean('billing_same_address_flag')->default(true)->comment('請求先住所同一フラグ');
            }

            // 決済関連
            if (!Schema::hasColumn('users', 'payment_status')) {
                $table->boolean('payment_status')->default(false)->comment('決済ステータス');
            }
            if (!Schema::hasColumn('users', 'last_update_payment')) {
                $table->timestamp('last_update_payment')->nullable()->comment('最終決済更新日時');
            }
            if (!Schema::hasColumn('users', 'admin_id_update_payment')) {
                $table->unsignedBigInteger('admin_id_update_payment')->nullable()->comment('決済更新管理者ID');
            }
            if (!Schema::hasColumn('users', 'last_change_status')) {
                $table->timestamp('last_change_status')->nullable()->comment('最終ステータス変更日時');
            }

            // 認証関連（user_credentialsへ移行するが、互換性のため残す）
            if (!Schema::hasColumn('users', 'verified')) {
                $table->boolean('verified')->default(false)->comment('認証確認済みフラグ');
            }

            // インデックス
            $table->index('type');
            $table->index('account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'type',
                'last_name_kanji',
                'first_name_kanji',
                'last_name_kana',
                'first_name_kana',
                'line_name',
                'group_name',
                'group_name_kana',
                'postal_code',
                'street_address',
                'building',
                'birthday',
                'phone',
                'email_send',
                'account_type',
                'account_number',
                'customer_id',
                'is_black',
                'memo',
                'billing_same_address_flag',
                'payment_status',
                'last_update_payment',
                'admin_id_update_payment',
                'last_change_status',
                'verified',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
