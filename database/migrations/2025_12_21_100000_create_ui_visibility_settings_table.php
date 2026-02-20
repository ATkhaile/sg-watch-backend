<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * UI表示設定テーブル - ロール別のUI表示/非表示をマトリクス形式で管理
     */
    public function up(): void
    {
        // UI要素マスタテーブル
        Schema::create('ui_elements', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->comment('UI要素の種類: member_feature, admin_menu');
            $table->string('key', 100)->comment('UI要素のキー: feed, chat, news, users等');
            $table->string('display_name')->comment('表示名');
            $table->string('description')->nullable()->comment('説明');
            $table->string('category')->nullable()->comment('カテゴリ: Webサイト管理, 会員管理等');
            $table->integer('display_order')->default(0)->comment('表示順');
            $table->boolean('is_active')->default(true)->comment('有効/無効');
            $table->timestamps();

            $table->unique(['type', 'key']);
            $table->index('type');
            $table->index('is_active');
        });

        // UI表示設定テーブル（ロール別）
        Schema::create('ui_visibility_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ui_element_id')->constrained('ui_elements')->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade')->comment('nullの場合はゲスト用');
            $table->enum('visibility', ['public', 'development', 'hidden'])->default('hidden')->comment('表示状態');
            $table->timestamps();

            // ロール + UI要素でユニーク
            $table->unique(['ui_element_id', 'role_id'], 'ui_visibility_role_element_unique');
        });

        // 初期データ投入: メンバー向け機能
        $memberFeatures = [
            ['key' => 'feed', 'display_name' => 'フィード', 'description' => 'タイムライン・投稿機能', 'category' => 'メンバー機能', 'display_order' => 1],
            ['key' => 'chat', 'display_name' => 'チャット', 'description' => 'メッセージ・チャット機能', 'category' => 'メンバー機能', 'display_order' => 2],
            ['key' => 'shop', 'display_name' => 'ショップ', 'description' => '商品購入機能', 'category' => 'メンバー機能', 'display_order' => 3],
            ['key' => 'profile', 'display_name' => 'プロフィール', 'description' => 'プロフィール表示・編集', 'category' => 'メンバー機能', 'display_order' => 4],
            ['key' => 'purchased_products', 'display_name' => '購入済み商品', 'description' => '購入した商品一覧', 'category' => 'メンバー機能', 'display_order' => 5],
        ];

        foreach ($memberFeatures as $feature) {
            DB::table('ui_elements')->insert([
                'type' => 'member_feature',
                'key' => $feature['key'],
                'display_name' => $feature['display_name'],
                'description' => $feature['description'],
                'category' => $feature['category'],
                'display_order' => $feature['display_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 初期データ投入: 管理者向けメニュー
        $adminMenus = [
            // Webサイト管理
            ['key' => 'news', 'display_name' => 'お知らせ管理', 'description' => '投稿・編集・公開設定', 'category' => 'Webサイト管理', 'display_order' => 1],
            ['key' => 'faqs', 'display_name' => 'FAQ管理', 'description' => 'よくある質問の編集', 'category' => 'Webサイト管理', 'display_order' => 2],
            // 会員管理
            ['key' => 'users', 'display_name' => '会員一覧', 'description' => '会員情報の閲覧・編集', 'category' => '会員管理', 'display_order' => 3],
            ['key' => 'chats', 'display_name' => 'チャット管理', 'description' => '会員とのメッセージ対応', 'category' => '会員管理', 'display_order' => 4],
            // コンテンツ管理
            ['key' => 'columns', 'display_name' => 'コラム管理', 'description' => '記事の作成・編集・公開', 'category' => 'コンテンツ管理', 'display_order' => 5],
            ['key' => 'media', 'display_name' => 'メディア管理', 'description' => '画像・動画・ファイルの管理', 'category' => 'コンテンツ管理', 'display_order' => 6],
            // 販売コンテンツ
            ['key' => 'plans', 'display_name' => 'プラン管理', 'description' => 'サブスクプラン設定', 'category' => '販売コンテンツ', 'display_order' => 7],
            ['key' => 'products', 'display_name' => '商品管理', 'description' => '買切り商品の管理', 'category' => '販売コンテンツ', 'display_order' => 8],
            // 決済管理
            ['key' => 'payment_triggers', 'display_name' => '決済トリガー一覧', 'description' => 'Stripe PaymentLinks設定と紐付け', 'category' => '決済管理', 'display_order' => 9],
            ['key' => 'subscriptions', 'display_name' => 'サブスクリプション一覧', 'description' => 'ご契約いただいたサブスクの一覧', 'category' => '決済管理', 'display_order' => 10],
            ['key' => 'transactions', 'display_name' => '取引一覧', 'description' => 'システム関係アカウントの取引', 'category' => '決済管理', 'display_order' => 11],
            // 外部連携
            ['key' => 'stripe_accounts', 'display_name' => 'Stripeアカウント管理', 'description' => 'システムに紐づけられたアカウント', 'category' => '外部連携', 'display_order' => 12],
            // マスタ管理
            ['key' => 'categories', 'display_name' => 'カテゴリ管理', 'description' => 'カテゴリの設定と管理', 'category' => 'マスタ管理', 'display_order' => 13],
            ['key' => 'tags', 'display_name' => 'タグ管理', 'description' => 'タグの設定と管理', 'category' => 'マスタ管理', 'display_order' => 14],
            ['key' => 'memberships', 'display_name' => 'メンバーシップ管理', 'description' => '認可・権限・ポリシー設定', 'category' => 'マスタ管理', 'display_order' => 15],
            ['key' => 'ranks', 'display_name' => 'ランク設定', 'description' => '会員ランクの管理', 'category' => 'マスタ管理', 'display_order' => 16],
            ['key' => 'roles', 'display_name' => 'ロール管理', 'description' => 'ロールの設定と管理', 'category' => 'マスタ管理', 'display_order' => 17],
            ['key' => 'permissions', 'display_name' => '権限管理', 'description' => '権限の設定と管理', 'category' => 'マスタ管理', 'display_order' => 18],
            // 設定
            ['key' => 'settings', 'display_name' => 'システム設定', 'description' => '外部連携・メンテナンス', 'category' => '設定', 'display_order' => 19],
            ['key' => 'ui_visibility', 'display_name' => 'UI表示設定', 'description' => 'UI要素の表示/非表示設定', 'category' => '設定', 'display_order' => 20],
        ];

        foreach ($adminMenus as $menu) {
            DB::table('ui_elements')->insert([
                'type' => 'admin_menu',
                'key' => $menu['key'],
                'display_name' => $menu['display_name'],
                'description' => $menu['description'],
                'category' => $menu['category'],
                'display_order' => $menu['display_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 初期データ投入: 管理者向けフッター
        $adminFooters = [
            ['key' => 'admin_home', 'display_name' => 'ホーム', 'description' => '管理者ホーム画面', 'category' => '管理者フッター', 'display_order' => 1],
            ['key' => 'admin_site', 'display_name' => 'サイト管理', 'description' => 'サイト管理機能', 'category' => '管理者フッター', 'display_order' => 2],
            ['key' => 'admin_users', 'display_name' => '会員管理', 'description' => '会員管理機能', 'category' => '管理者フッター', 'display_order' => 3],
            ['key' => 'admin_menu', 'display_name' => '全メニュー', 'description' => '全メニュー表示', 'category' => '管理者フッター', 'display_order' => 4],
        ];

        foreach ($adminFooters as $footer) {
            DB::table('ui_elements')->insert([
                'type' => 'admin_footer',
                'key' => $footer['key'],
                'display_name' => $footer['display_name'],
                'description' => $footer['description'],
                'category' => $footer['category'],
                'display_order' => $footer['display_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_visibility_settings');
        Schema::dropIfExists('ui_elements');
    }
};
