# プロジェクト固有コード調査レポート

**作成日:** 2026-01-25
**対象:** `/api` ディレクトリ
**目的:** TERAKONA、TDBS、CUSTOMER等のプロジェクト固有情報の使用状況と影響範囲の把握

---

## 1. 概要

API内に **3つのプロジェクト固有システム** が存在している：

| システム | 説明 | デモURL |
|---------|------|---------|
| **TERAKONA** | メインプラットフォーム | terakona.demo-dev.xyz |
| **TDBS** | 独立サブシステム（ゴルフ/サウナ施設管理） | - |
| **CUSTOMER** | 汎用顧客プラットフォーム | elc-general.demo-dev.xyz |

---

## 2. レイヤー別影響範囲

### 2.1 設定層（Configuration Layer）

#### .env ファイル

**TERAKONA設定（87-97行, 138-145行）:**
```env
# Email 2FA設定
TERAKONA_ENABLE_EMAIL_2FA=true
TERAKONA_EMAIL_2FA_CODE_EXPIRE=15
TERAKONA_ENABLE_EMAIL_VERIFICATION=true
TERAKONA_EMAIL_VERIFICATION_TIMEOUT=15

# フロントエンドURL
BASE_FRONTEND_URL_TERAKONA="https://terakona.demo-dev.xyz/"
BASE_FRONTEND_URL_TERAKONA_FORGET_PASSWORD="https://terakona.demo-dev.xyz/auth/set-password"
BASE_FRONTEND_URL_TERAKONA_VERIFY_REGISTRATION="https://terakona.demo-dev.xyz/verify-registration"
BASE_FRONTEND_URL_TERAKONA_CUSTOMER_PLANS="https://terakona.demo-dev.xyz/customer/plans"

# OAuth設定
TERAKONA_LINE_CLIENT_ID=...
TERAKONA_LINE_CLIENT_SECRET=...
TERAKONA_LINE_REDIRECT_URL=...
TERAKONA_GOOGLE_CLIENT_ID=...
TERAKONA_GOOGLE_CLIENT_SECRET=...
TERAKONA_GOOGLE_REDIRECT_URL=...
```

**CUSTOMER設定（101-111行, 147-153行）:**
```env
# Email 2FA設定
CUSTOMER_ENABLE_EMAIL_2FA=true
CUSTOMER_EMAIL_2FA_CODE_EXPIRE=15
CUSTOMER_ENABLE_EMAIL_VERIFICATION=true
CUSTOMER_EMAIL_VERIFICATION_TIMEOUT=15

# フロントエンドURL
BASE_FRONTEND_URL_CUSTOMER="https://elc-general.demo-dev.xyz/"
BASE_FRONTEND_URL_CUSTOMER_FORGET_PASSWORD="https://elc-general.demo-dev.xyz/auth/set-password"
BASE_FRONTEND_URL_CUSTOMER_VERIFY_REGISTRATION="https://elc-general.demo-dev.xyz/verify-registration"
BASE_FRONTEND_URL_CUSTOMER_PLANS="https://elc-general.demo-dev.xyz/customer/plans"

# OAuth設定
CUSTOMER_GOOGLE_CLIENT_ID=...
CUSTOMER_GOOGLE_CLIENT_SECRET=...
CUSTOMER_GOOGLE_REDIRECT_URL=...
CUSTOMER_LINE_CLIENT_ID=...
CUSTOMER_LINE_CLIENT_SECRET=...
CUSTOMER_LINE_REDIRECT_URL=...
```

#### config/services.php（65-83行）

```php
// TERAKONA OAuth
'terakona_google' => [
    'client_id' => env('TERAKONA_GOOGLE_CLIENT_ID'),
    'client_secret' => env('TERAKONA_GOOGLE_CLIENT_SECRET'),
    'redirect' => env('TERAKONA_GOOGLE_REDIRECT_URL'),
],
'terakona_line' => [
    'client_id' => env('TERAKONA_LINE_CLIENT_ID'),
    'client_secret' => env('TERAKONA_LINE_CLIENT_SECRET'),
    'redirect' => env('TERAKONA_LINE_REDIRECT_URL'),
],

// CUSTOMER OAuth
'customer_google' => [...],
'customer_line' => [...],
```

#### config/usecase_groups.php（279-207行）

TDBSグループ定義（9項目）:
- TdbsMember（TDBS会員）
- TdbsNews（TDBSニュース）
- TdbsShop（TDBSショップ）
- TdbsGolphShop（TDBSゴルフショップ）
- TdbsSaunaShop（TDBSサウナショップ）
- TdbsCoupon（TDBSクーポン）
- TdbsTerm（TDBS利用規約）
- TdbsPlan（TDBSプラン）
- TdbsSchedule（TDBSスケジュール）

#### config/permissions.php（1134-1330行）

TDBS権限セット（72+エントリ）:

| グループ | 権限数 | 権限例 |
|---------|-------|--------|
| TdbsMember | 3 | tdbs-list-member, tdbs-detail-member, tdbs-update-member |
| TdbsNews | 5 | tdbs-create-news, tdbs-delete-news, tdbs-find-news, etc. |
| TdbsShop | 2 | tdbs-list-shop, tdbs-find-shop |
| TdbsGolphShop | 1 | tdbs-golph-update-shop |
| TdbsSaunaShop | 1 | tdbs-sauna-update-shop |
| TdbsCoupon | 5 | tdbs-golph-list-coupon, tdbs-golph-create-coupon, etc. |
| TdbsTerm | 2 | tdbs-term-detail, tdbs-term-update |
| TdbsPlan | 5 | tdbs-golph-list-plan, tdbs-golph-create-plan, etc. |
| TdbsSchedule | 5 | tdbs-golph-list-schedule, tdbs-golph-import-schedule, etc. |

---

### 2.2 ルーティング層（Routing Layer）

#### routes/api.php（63-66行）

```php
// TDBS独立したシステム用のルート
require __DIR__ . '/api/tdbs.php';
```

#### routes/api/tdbs.php（14-82行）

```
プレフィックス: /api/tdbs
├── /admin
│   ├── /member
│   │   ├── GET '' → GetAllMembersAction
│   │   ├── GET '{id}' → GetMemberDetailAction
│   │   └── PUT '{id}' → UpdateMemberAction
│   ├── /news
│   │   ├── GET '' → GetAllNewsAction
│   │   ├── GET '{id}' → GetNewsDetailAction
│   │   ├── POST '' → CreateNewsAction
│   │   ├── PUT '{id}' → UpdateNewsAction
│   │   └── DELETE '{id}' → DeleteNewsAction
│   ├── /shop
│   │   ├── GET '' → GetAllShopAction
│   │   └── GET '{id}' → GetShopDetailAction
│   ├── /{shop_id}
│   │   ├── /golph
│   │   │   ├── PUT /shop → UpdateShopAction
│   │   │   ├── /coupon [CRUD]
│   │   │   ├── /plan [CRUD]
│   │   │   └── /schedule [Import/template/CRUD]
│   │   ├── PUT /sauna/shop → UpdateShopAction
│   │   ├── GET /term → GetTermDetailAction
│   │   └── PUT /term → UpdateTermAction
│   └── /system-setting
│       ├── GET '{type}' → GetSystemSettingDetailAction
│       └── PUT '{type}' → UpdateSystemSettingAction
└── /user (将来のユーザーAPI用プレースホルダー)
```

---

### 2.3 HTTPアクション層（Controller/Action Layer）

| プロジェクト | ディレクトリ | ファイル数 |
|------------|------------|----------|
| TERAKONA | `app/Docs/Actions/Api/Terakona/` | **62** |
| TDBS | `app/Docs/Actions/Api/Tdbs/` | **43** |
| CUSTOMER | `app/Docs/Actions/Api/Customer/` | **2** |

#### TERAKONA アクション内訳

| カテゴリ | ファイル数 | 内容 |
|---------|----------|------|
| Auth | 9 | Login, Register, ForgotPassword, ResetPassword, VerifyLogin, UserInfo, Logout, LineCallback, GoogleCallback |
| News | 8 | CRUD操作 |
| Plans | 6 | プラン管理、顧客プラン取得 |
| Columns | 8 | コラム記事管理 |
| FAQ | 7 | FAQ管理・公開 |
| Stripe | 11 | アカウント管理、決済プラン、サブスクリプション、請求/取引処理 |
| Tags | 1 | タグ管理 |

#### TDBS アクション内訳

| カテゴリ | ファイル数 | 内容 |
|---------|----------|------|
| Member | 3 | GetAllMembers, GetMemberDetail, UpdateMember |
| News | 5 | CRUD操作 |
| Shop | 2 | GetAllShop, GetShopDetail |
| GolphShop | 1 | UpdateShop |
| SaunaShop | 1 | UpdateShop |
| Schedule | 6 | CRUD + Import + Template |
| Plan | 5 | CRUD操作 |
| Coupon | 5 | CRUD操作 |
| Term | 2 | GetTermDetail, UpdateTerm |
| SystemSetting | 2 | GetSystemSettingDetail, UpdateSystemSetting |

---

### 2.4 ドメイン/UseCase層（Business Logic Layer）

| プロジェクト | ディレクトリ | ファイル数 |
|------------|------------|----------|
| TDBS | `app/Domain/Tdbs/` | **131** |
| CUSTOMER | `app/Domain/Customer/` | **40** |

#### TDBS ドメイン構造

```
app/Domain/Tdbs/
├── Entity/           # データエンティティ
├── Golph/            # ゴルフ固有ロジック
│   ├── Coupon/       # クーポン管理
│   ├── Plan/         # プラン管理
│   ├── Schedule/     # スケジュール管理
│   └── Shop/         # ゴルフショップ管理
├── Member/           # 会員管理
├── News/             # ニュース管理
├── Sauna/            # サウナ固有ロジック
│   └── Shop/         # サウナショップ管理
├── Shop/             # 共通ショップ管理
├── SystemSetting/    # システム設定
└── Term/             # 利用規約管理
```

#### CUSTOMER ドメイン

主に認証関連:
- RegisterUserUseCase
- ForgotPasswordUseCase
- LoginUseCase

**注意:** ドメイン層からTERAKONA設定を直接参照している箇所あり:
```php
// app/Domain/Customer/Auth/RegisterUserUseCase.php:47
config('auth.terakona_email_verification.enabled')
```

---

### 2.5 モデル層（Data Model Layer）

| プロジェクト | ディレクトリ | ファイル数 |
|------------|------------|----------|
| TDBS | `app/Models/Tdbs/` | **17** |
| 基底クラス | `app/Models/TdbsModel.php` | **1** |

#### TDBSモデル一覧

| モデル | 説明 |
|--------|------|
| Account | ユーザーアカウント |
| BookingTmp | 一時予約 |
| Card | 決済カード |
| Contact | 連絡先情報 |
| Coupon | クーポン |
| Holiday | 休日定義 |
| News | ニュース記事 |
| Option | オプション/設定 |
| PaymentHistory | 決済履歴 |
| PaymentPlan | 決済プラン定義 |
| Plan | プラン（コース/サービス） |
| Prefecture | 都道府県データ |
| Reservation | 予約 |
| Schedule | スケジュール枠 |
| Shop | ショップ/施設情報 |
| SystemSetting | システム設定 |
| UserPlan | ユーザー選択プラン |

#### 基底モデルクラス

```php
// app/Models/TdbsModel.php
class TdbsModel extends Model implements AuditableContract
{
    protected $tablePrefix = 'tdbs_';
    // 全TDBSモデルのテーブル名に自動でtdbs_プレフィックス付与
}
```

---

### 2.6 インフラストラクチャ層（Repository/Infrastructure Layer）

| ディレクトリ | 確認済みファイル |
|------------|----------------|
| `app/Domain/Tdbs/Shop/Infrastructure/` | DbShopInfrastructure.php |
| `app/Domain/Tdbs/Member/Infrastructure/` | DbMembersInfrastructure.php |
| `app/Domain/Tdbs/Golph/Schedule/Infrastructure/` | DbSchedulesInfrastructure.php |

**実装パターン:**
- `Db*Infrastructure` 命名規則
- Repositoryインターフェース実装
- TDBSモデル経由でtdbs_プレフィックス付きテーブルにアクセス

---

### 2.7 HTTPリクエスト/リソース層

#### Request（バリデーション）

```
app/Http/Requests/Api/Tdbs/
├── Golph/
│   ├── Plan/
│   │   ├── CreatePlanRequest.php
│   │   ├── GetPlanDetailRequest.php
│   │   ├── GetAllPlansRequest.php
│   │   ├── DeletePlanRequest.php
│   │   └── UpdatePlanRequest.php
│   └── Schedule/
│       ├── GetAllSchedulesRequest.php
│       ├── GetScheduleDetailRequest.php
│       ├── ImportScheduleRequest.php
│       └── UpdateScheduleRequest.php
└── Sauna/
    └── Shop/
        └── UpdateShopRequest.php
```

#### Resource（レスポンス変換）

```
app/Http/Resources/Api/Tdbs/
└── Golph/
    ├── Schedule/
    │   ├── GetScheduleDetailActionResource.php
    │   └── GetAllSchedulesActionResource.php
    └── Plan/
        ├── GetPlanDetailActionResource.php
        └── GetAllPlansActionResource.php
```

---

### 2.8 データベース層（Database Layer）

#### Seeder

```
database/seeders/Tdbs/
├── TdbsShopsTableSeeder.php    # 5店舗の初期データ
└── TdbsOptionsTableSeeder.php  # オプション設定初期データ
```

**TdbsShopsTableSeeder内容:**
- ID 1: Golph.club Suminoe（ゴルフ施設）
- ID 2-5: SAUNA & CURRY URI各店舗（サウナ施設）
- 各店舗: 詳細情報、料金、LINE ID、Google Maps埋め込み、トークン

#### Migration

`tdbs_`プレフィックス付きテーブル（17テーブル相当）:
- tdbs_shops
- tdbs_plans
- tdbs_schedules
- tdbs_coupons
- tdbs_reservations
- tdbs_accounts
- tdbs_cards
- tdbs_news
- tdbs_holidays
- tdbs_contacts
- tdbs_payment_histories
- tdbs_system_settings
- tdbs_user_plans
- etc.

---

## 3. 影響範囲サマリー

| レイヤー | TERAKONA | TDBS | CUSTOMER | 推定ファイル数 |
|---------|:--------:|:----:|:--------:|-------------:|
| 設定 | ○ | ○ | ○ | ~5 |
| ルーティング | △ | ○ | △ | ~2 |
| Action | ○ | ○ | ○ | **107** |
| Domain/UseCase | △ | ○ | ○ | **171** |
| Model | - | ○ | - | **18** |
| Infrastructure | - | ○ | - | ~10 |
| Request/Resource | - | ○ | - | ~15 |
| Database | - | ○ | - | ~20 |
| **合計** | | | | **~350+** |

---

## 4. アーキテクチャ上の特徴

### 4.1 TDBS の分離度（高）

- **データベース分離:** `tdbs_`テーブルプレフィックスによる完全分離
- **ルーティング分離:** `/api/tdbs`独立名前空間
- **ドメイン分離:** `app/Domain/Tdbs/`配下に完全なドメイン層
- **モデル分離:** 専用基底クラス`TdbsModel`

### 4.2 TERAKONA/CUSTOMER の分離度（低〜中）

- **設定依存:** 環境変数でプロジェクト名がハードコード
- **ドメイン層での直接参照:** `config('auth.terakona_...')`形式の参照
- **OAuth設定の分散:** services.phpでプロジェクト別に定義

### 4.3 認証方式の多様性

| システム | 認証方式 |
|---------|---------|
| TERAKONA | Email 2FA, OAuth (Google/LINE), セッションベース |
| TDBS | 管理者APIアクセスのみ |
| CUSTOMER | TERAKONA類似 + 専用OAuth認証情報 |

---

## 5. 抽象化に向けた課題

### 5.1 設定のハードコード

**問題箇所:**
- `.env`内のプロジェクト固有変数名
- `config/services.php`のOAuth設定キー
- ドメイン層からの直接config参照

### 5.2 命名規則の不統一

- TDBS: `tdbs-`プレフィックス（権限）、`Tdbs`プレフィックス（クラス）
- TERAKONA: 明示的なプレフィックスなし
- CUSTOMER: `customer_`プレフィックス（設定のみ）

### 5.3 ドメイン層の結合

- `app/Domain/Customer/`が`terakona`設定を直接参照
- プロジェクト横断的な共通ロジックの欠如

---

## 6. 次のステップ

1. **マルチテナント設計パターンの選定**
2. **テナント識別子の統一規格策定**
3. **設定の動的解決機構の設計**
4. **段階的マイグレーション計画の立案**
