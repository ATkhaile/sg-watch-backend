# ELC Core System Seeders

ELCシステムのコアデータを生成するSeederファイル群です。

## 📁 構成

```
ElcCore/
├── PermissionSeeder.php                    # 権限とロールの作成
├── UserSeeder.php                          # 初期ユーザーの作成
├── EntitlementTypeSeeder.php               # エンタイトルメントタイプの作成
├── MembershipActionSettingsSeeder.php      # メンバーシップアクション設定
├── ChatbotSeeder.php                       # Chatbot初期データ
└── README.md                               # このファイル
```

## 🚀 使用方法

### 標準実行（DatabaseSeederから自動実行）

通常のシーディングコマンドで自動的に実行されます:

```bash
php artisan db:seed
```

これにより、以下の順序でコアデータが作成されます:
1. **PermissionSeeder** - 権限とロール
2. **UserSeeder** - 初期ユーザー
3. **EntitlementTypeSeeder** - エンタイトルメントタイプ
4. **MembershipActionSettingsSeeder** - メンバーシップアクション設定
5. **ChatbotSeeder** - Chatbot初期データ

### 個別実行

特定のSeederのみを実行:

```bash
# 権限のみ
php artisan db:seed --class=Database\\Seeders\\ElcCore\\PermissionSeeder

# ユーザーのみ
php artisan db:seed --class=Database\\Seeders\\ElcCore\\UserSeeder

# エンタイトルメントタイプのみ
php artisan db:seed --class=Database\\Seeders\\ElcCore\\EntitlementTypeSeeder

# メンバーシップアクション設定のみ
php artisan db:seed --class=Database\\Seeders\\ElcCore\\MembershipActionSettingsSeeder

# Chatbotのみ
php artisan db:seed --class=Database\\Seeders\\ElcCore\\ChatbotSeeder
```

## 📊 生成データ詳細

### 1. PermissionSeeder

**役割**: システムの権限とロールを作成

**生成データ**:
- `config/permissions.php` から全権限を読み込み
- `admin` ロール（全権限）
- `user` ロール（一般ユーザー向け権限）

**権限ルール**:
- **除外グループ**: 管理者専用機能（Authorization, Users, Maintenanceなど）はuserロールから除外
- **表示のみ許可**: Category, Tags, Newsなどは表示のみ許可
- **特定権限のみ**: DailyBonusは取得と作成のみ許可

### 2. UserSeeder

**役割**: 初期ユーザーアカウントを作成

**生成データ**:
- **管理者**:
  - Email: `account+init@gameagelayer.com`
  - Password: `Laravel@2025`
  - Role: `admin`
- **テストユーザー** (2名):
  - `account+general1@gameagelayer.com`
  - `account+general2@gameagelayer.com`
  - Password: `Laravel@2025`
  - Role: `user`

### 3. EntitlementTypeSeeder

**役割**: エンタイトルメントタイプ（機能権限）を作成

**生成データ**:
- `admin_ui_access` - 管理者画面のUIの表示
- `paywall_disabled` - 未加入時のPaywall非表示
- `shop_access` - ショップ機能利用
- `profile_access` - マイプロフィールの確認

### 4. MembershipActionSettingsSeeder

**役割**: メンバーシップアクション関連のシステム設定

**生成データ**:
- `membership_action_default`: デフォルトアクション（none, membership_only, full）
- `membership_action_skip_confirmation`: 確認スキップ（true/false）

### 5. ChatbotSeeder

**役割**: デフォルトChatbotの作成

**生成データ**:
- Chatbot ID: `community-chatbot-demo-1`
- 表示名: `フルー（Flu）`
- 色: `emerald`
- 音声通知: 有効

## ⚠️ 注意事項

- これらのSeederは **システムのコアデータ** を生成します
- データベースをリセットする場合は必ず実行してください
- `firstOrCreate` / `updateOrCreate` を使用しているため、複数回実行しても安全です
- パスワードは本番環境では必ず変更してください

## 🔗 関連モデル

- `App\Models\Permission`
- `App\Models\Role`
- `App\Models\User`
- `App\Models\EntitlementType`
- `App\Models\SystemSetting`
- `App\Models\Chatbot`

## 🎯 使用例

```bash
# 1. データベースをリセット
php artisan migrate:fresh

# 2. コアデータをシード（自動実行）
php artisan db:seed

# 3. コミュニティデータをシード（別途実行）
php artisan db:seed --class=Database\\Seeders\\ElcCommunity\\CommunityMasterSeeder
```

## 📝 開発者向けメモ

### DatabaseSeeder.phpでの実行順序

`DatabaseSeeder.php` で以下の順序で自動実行されます:

```php
$this->call([
    \Database\Seeders\ElcCore\PermissionSeeder::class,
    \Database\Seeders\ElcCore\UserSeeder::class,
    \Database\Seeders\ElcCore\EntitlementTypeSeeder::class,
    \Database\Seeders\ElcCore\MembershipActionSettingsSeeder::class,
    \Database\Seeders\ElcCore\ChatbotSeeder::class,
]);
```

### 権限管理のカスタマイズ

`PermissionSeeder.php` の定数を変更することで、userロールの権限を調整できます:

- `USER_EXCLUDED_GROUPS`: 完全に除外するグループ
- `USER_VIEW_ONLY_GROUPS`: 表示のみ許可するグループ
- `USER_ALLOWED_SPECIFIC_PERMISSIONS`: 特定の権限のみ許可
