<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * UI要素のtype/key形式を新形式に変更
     */
    public function up(): void
    {
        // 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 既存データを削除して新形式で再作成
        DB::table('ui_visibility_settings')->truncate();
        DB::table('ui_elements')->truncate();

        // 外部キー制約を再有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // 新しいUI要素を挿入
        $elements = [
            // ========================================
            // Member Shortcut (メンバー画面のクイックアクション)
            // ========================================
            ['type' => 'member_shortcut', 'key' => 'member.feed.create', 'display_name' => '投稿作成', 'category' => 'member', 'display_order' => 1],
            ['type' => 'member_shortcut', 'key' => 'member.chat', 'display_name' => 'チャット', 'category' => 'member', 'display_order' => 2],
            ['type' => 'member_shortcut', 'key' => 'member.shop', 'display_name' => 'ショップ', 'category' => 'member', 'display_order' => 3],
            ['type' => 'member_shortcut', 'key' => 'member.profile', 'display_name' => 'プロフィール', 'category' => 'member', 'display_order' => 4],
            ['type' => 'member_shortcut', 'key' => 'admin.home', 'display_name' => '管理画面', 'category' => 'member', 'display_order' => 5],

            // ========================================
            // Member Menu (メンバー画面のメニュー)
            // ========================================
            ['type' => 'member_menu', 'key' => 'member.news', 'display_name' => 'お知らせ', 'category' => 'member', 'display_order' => 1],
            ['type' => 'member_menu', 'key' => 'member.faqs', 'display_name' => 'よくある質問', 'category' => 'member', 'display_order' => 2],
            ['type' => 'member_menu', 'key' => 'member.chat', 'display_name' => 'チャット', 'category' => 'member', 'display_order' => 3],
            ['type' => 'member_menu', 'key' => 'member.columns', 'display_name' => 'コラム', 'category' => 'member', 'display_order' => 4],
            ['type' => 'member_menu', 'key' => 'member.media', 'display_name' => 'メディア', 'category' => 'member', 'display_order' => 5],
            ['type' => 'member_menu', 'key' => 'member.plans', 'display_name' => 'プラン', 'category' => 'member', 'display_order' => 6],
            ['type' => 'member_menu', 'key' => 'member.products', 'display_name' => '商品', 'category' => 'member', 'display_order' => 7],
            ['type' => 'member_menu', 'key' => 'member.purchased_products', 'display_name' => '購入済み商品', 'category' => 'member', 'display_order' => 8],
            ['type' => 'member_menu', 'key' => 'admin.home', 'display_name' => '管理画面', 'category' => 'member', 'display_order' => 9],
            ['type' => 'member_menu', 'key' => 'member.settings', 'display_name' => '設定', 'category' => 'member', 'display_order' => 10],

            // ========================================
            // Member Footer (メンバー画面のフッター)
            // ========================================
            ['type' => 'member_footer', 'key' => 'member.home', 'display_name' => 'ホーム', 'category' => 'member', 'display_order' => 1],
            ['type' => 'member_footer', 'key' => 'member.feed', 'display_name' => 'フィード', 'category' => 'member', 'display_order' => 2],
            ['type' => 'member_footer', 'key' => 'member.chat', 'display_name' => 'チャット', 'category' => 'member', 'display_order' => 3],
            ['type' => 'member_footer', 'key' => 'member.shop', 'display_name' => 'ショップ', 'category' => 'member', 'display_order' => 4],
            ['type' => 'member_footer', 'key' => 'member.profile', 'display_name' => 'プロフィール', 'category' => 'member', 'display_order' => 5],

            // ========================================
            // Admin Shortcut (管理画面のクイックアクション)
            // ========================================
            ['type' => 'admin_shortcut', 'key' => 'admin.news.create', 'display_name' => 'お知らせ作成', 'category' => 'admin', 'display_order' => 1],
            ['type' => 'admin_shortcut', 'key' => 'admin.chats', 'display_name' => 'チャット', 'category' => 'admin', 'display_order' => 2],
            ['type' => 'admin_shortcut', 'key' => 'admin.users', 'display_name' => 'ユーザー', 'category' => 'admin', 'display_order' => 3],
            ['type' => 'admin_shortcut', 'key' => 'admin.profile', 'display_name' => 'プロフィール', 'category' => 'admin', 'display_order' => 4],
            ['type' => 'admin_shortcut', 'key' => 'member.home', 'display_name' => 'メンバー画面', 'category' => 'admin', 'display_order' => 5],

            // ========================================
            // Admin Menu (管理画面のメニュー)
            // ========================================
            ['type' => 'admin_menu', 'key' => 'admin.news', 'display_name' => 'お知らせ', 'category' => 'admin', 'display_order' => 1],
            ['type' => 'admin_menu', 'key' => 'admin.faqs', 'display_name' => 'よくある質問', 'category' => 'admin', 'display_order' => 2],
            ['type' => 'admin_menu', 'key' => 'admin.users', 'display_name' => 'ユーザー', 'category' => 'admin', 'display_order' => 3],
            ['type' => 'admin_menu', 'key' => 'admin.chats', 'display_name' => 'チャット', 'category' => 'admin', 'display_order' => 4],
            ['type' => 'admin_menu', 'key' => 'admin.columns', 'display_name' => 'コラム', 'category' => 'admin', 'display_order' => 5],
            ['type' => 'admin_menu', 'key' => 'admin.media', 'display_name' => 'メディア', 'category' => 'admin', 'display_order' => 6],
            ['type' => 'admin_menu', 'key' => 'admin.plans', 'display_name' => 'プラン', 'category' => 'admin', 'display_order' => 7],
            ['type' => 'admin_menu', 'key' => 'admin.products', 'display_name' => '商品', 'category' => 'admin', 'display_order' => 8],
            ['type' => 'admin_menu', 'key' => 'admin.payment_triggers', 'display_name' => '決済トリガー', 'category' => 'admin', 'display_order' => 9],
            ['type' => 'admin_menu', 'key' => 'admin.subscriptions', 'display_name' => 'サブスクリプション', 'category' => 'admin', 'display_order' => 10],
            ['type' => 'admin_menu', 'key' => 'admin.transactions', 'display_name' => 'トランザクション', 'category' => 'admin', 'display_order' => 11],
            ['type' => 'admin_menu', 'key' => 'admin.stripe_accounts', 'display_name' => 'Stripeアカウント', 'category' => 'admin', 'display_order' => 12],
            ['type' => 'admin_menu', 'key' => 'admin.categories', 'display_name' => 'カテゴリ', 'category' => 'admin', 'display_order' => 13],
            ['type' => 'admin_menu', 'key' => 'admin.tags', 'display_name' => 'タグ', 'category' => 'admin', 'display_order' => 14],
            ['type' => 'admin_menu', 'key' => 'admin.memberships', 'display_name' => 'メンバーシップ', 'category' => 'admin', 'display_order' => 15],
            ['type' => 'admin_menu', 'key' => 'admin.ranks', 'display_name' => 'ランク', 'category' => 'admin', 'display_order' => 16],
            ['type' => 'admin_menu', 'key' => 'admin.roles', 'display_name' => 'ロール', 'category' => 'admin', 'display_order' => 17],
            ['type' => 'admin_menu', 'key' => 'admin.permissions', 'display_name' => '権限', 'category' => 'admin', 'display_order' => 18],
            ['type' => 'admin_menu', 'key' => 'member.home', 'display_name' => 'メンバー画面', 'category' => 'admin', 'display_order' => 19],
            ['type' => 'admin_menu', 'key' => 'admin.settings', 'display_name' => '設定', 'category' => 'admin', 'display_order' => 20],
            ['type' => 'admin_menu', 'key' => 'admin.ui_visibility', 'display_name' => 'UI表示設定', 'category' => 'admin', 'display_order' => 21],

            // ========================================
            // Admin Footer (管理画面のフッター)
            // ========================================
            ['type' => 'admin_footer', 'key' => 'admin.home', 'display_name' => 'ホーム', 'category' => 'admin', 'display_order' => 1],
            ['type' => 'admin_footer', 'key' => 'admin.news', 'display_name' => 'お知らせ', 'category' => 'admin', 'display_order' => 2],
            ['type' => 'admin_footer', 'key' => 'admin.products', 'display_name' => '商品', 'category' => 'admin', 'display_order' => 3],
            ['type' => 'admin_footer', 'key' => 'admin.users', 'display_name' => 'ユーザー', 'category' => 'admin', 'display_order' => 4],
            ['type' => 'admin_footer', 'key' => 'admin.menu', 'display_name' => 'メニュー', 'category' => 'admin', 'display_order' => 5],
        ];

        $now = now();
        foreach ($elements as &$element) {
            $element['is_active'] = true;
            $element['created_at'] = $now;
            $element['updated_at'] = $now;
        }

        DB::table('ui_elements')->insert($elements);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // 新形式のデータを削除して旧形式に戻す
        DB::table('ui_visibility_settings')->truncate();
        DB::table('ui_elements')->truncate();

        // 外部キー制約を再有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // 旧形式のUI要素を挿入
        $elements = [
            // Member Features (旧形式)
            ['type' => 'member_feature', 'key' => 'feed', 'display_name' => 'フィード', 'category' => 'member', 'display_order' => 1],
            ['type' => 'member_feature', 'key' => 'chat', 'display_name' => 'チャット', 'category' => 'member', 'display_order' => 2],
            ['type' => 'member_feature', 'key' => 'shop', 'display_name' => 'ショップ', 'category' => 'member', 'display_order' => 3],
            ['type' => 'member_feature', 'key' => 'profile', 'display_name' => 'プロフィール', 'category' => 'member', 'display_order' => 4],
            ['type' => 'member_feature', 'key' => 'purchased_products', 'display_name' => '購入済み商品', 'category' => 'member', 'display_order' => 5],

            // Admin Menu (旧形式)
            ['type' => 'admin_menu', 'key' => 'news', 'display_name' => 'お知らせ', 'category' => 'admin', 'display_order' => 1],
            ['type' => 'admin_menu', 'key' => 'faqs', 'display_name' => 'よくある質問', 'category' => 'admin', 'display_order' => 2],
            ['type' => 'admin_menu', 'key' => 'users', 'display_name' => 'ユーザー', 'category' => 'admin', 'display_order' => 3],
            ['type' => 'admin_menu', 'key' => 'chats', 'display_name' => 'チャット', 'category' => 'admin', 'display_order' => 4],
            ['type' => 'admin_menu', 'key' => 'columns', 'display_name' => 'コラム', 'category' => 'admin', 'display_order' => 5],
            ['type' => 'admin_menu', 'key' => 'media', 'display_name' => 'メディア', 'category' => 'admin', 'display_order' => 6],
            ['type' => 'admin_menu', 'key' => 'plans', 'display_name' => 'プラン', 'category' => 'admin', 'display_order' => 7],
            ['type' => 'admin_menu', 'key' => 'products', 'display_name' => '商品', 'category' => 'admin', 'display_order' => 8],
            ['type' => 'admin_menu', 'key' => 'payment_triggers', 'display_name' => '決済トリガー', 'category' => 'admin', 'display_order' => 9],
            ['type' => 'admin_menu', 'key' => 'subscriptions', 'display_name' => 'サブスクリプション', 'category' => 'admin', 'display_order' => 10],
            ['type' => 'admin_menu', 'key' => 'transactions', 'display_name' => 'トランザクション', 'category' => 'admin', 'display_order' => 11],
            ['type' => 'admin_menu', 'key' => 'stripe_accounts', 'display_name' => 'Stripeアカウント', 'category' => 'admin', 'display_order' => 12],
            ['type' => 'admin_menu', 'key' => 'categories', 'display_name' => 'カテゴリ', 'category' => 'admin', 'display_order' => 13],
            ['type' => 'admin_menu', 'key' => 'tags', 'display_name' => 'タグ', 'category' => 'admin', 'display_order' => 14],
            ['type' => 'admin_menu', 'key' => 'memberships', 'display_name' => 'メンバーシップ', 'category' => 'admin', 'display_order' => 15],
            ['type' => 'admin_menu', 'key' => 'ranks', 'display_name' => 'ランク', 'category' => 'admin', 'display_order' => 16],
            ['type' => 'admin_menu', 'key' => 'roles', 'display_name' => 'ロール', 'category' => 'admin', 'display_order' => 17],
            ['type' => 'admin_menu', 'key' => 'permissions', 'display_name' => '権限', 'category' => 'admin', 'display_order' => 18],
            ['type' => 'admin_menu', 'key' => 'settings', 'display_name' => '設定', 'category' => 'admin', 'display_order' => 19],
            ['type' => 'admin_menu', 'key' => 'ui_visibility', 'display_name' => 'UI表示設定', 'category' => 'admin', 'display_order' => 20],

            // Admin Footer (旧形式)
            ['type' => 'admin_footer', 'key' => 'admin_home', 'display_name' => 'ホーム', 'category' => 'admin', 'display_order' => 1],
            ['type' => 'admin_footer', 'key' => 'admin_site', 'display_name' => 'サイト', 'category' => 'admin', 'display_order' => 2],
            ['type' => 'admin_footer', 'key' => 'admin_users', 'display_name' => 'ユーザー', 'category' => 'admin', 'display_order' => 3],
            ['type' => 'admin_footer', 'key' => 'admin_menu', 'display_name' => 'メニュー', 'category' => 'admin', 'display_order' => 4],
        ];

        $now = now();
        foreach ($elements as &$element) {
            $element['is_active'] = true;
            $element['created_at'] = $now;
            $element['updated_at'] = $now;
        }

        DB::table('ui_elements')->insert($elements);
    }
};
