# マルチテナント対応 移行計画

**作成日:** 2026-01-25
**前提:** PROJECT_SPECIFIC_CODE_ANALYSIS.md の調査結果に基づく

---

## 1. マルチテナントパターンの選択肢

### 1.1 パターン比較

| パターン | 説明 | メリット | デメリット |
|---------|------|---------|-----------|
| **A. 設定ベース** | テナント識別子で設定を動的解決 | 実装が比較的簡単 | 設定ファイルが複雑化 |
| **B. サービスプロバイダー** | テナント専用のServiceProviderを登録 | Laravel標準パターン | 起動時オーバーヘッド |
| **C. ストラテジーパターン** | テナント毎の振る舞いをStrategy実装 | 拡張性が高い | 設計が複雑 |
| **D. データベース分離** | テナント毎にDB/スキーマ分離 | 完全分離 | 運用コスト高 |

### 1.2 推奨パターン: A + C のハイブリッド

現状のコードベースに最適なアプローチ:

1. **設定ベース** で環境変数・config を統一
2. **ストラテジーパターン** でテナント固有ロジックを分離
3. **既存のTDBS分離パターン** を参考に他テナントも同様に構造化

---

## 2. 移行フェーズ概要

```
Phase 1: 基盤構築（テナント識別・設定統一）
    ↓
Phase 2: 設定層の抽象化
    ↓
Phase 3: ドメイン層の抽象化
    ↓
Phase 4: ルーティング・アクション層の統一
    ↓
Phase 5: テスト・検証・移行完了
```

---

## 3. Phase 1: 基盤構築

### 3.1 テナント識別子の定義

**新規ファイル:** `app/Enums/Tenant.php`

```php
<?php

namespace App\Enums;

enum Tenant: string
{
    case ELC = 'elc';           // 管理システム（既存）
    case TERAKONA = 'terakona'; // TERAKONAプラットフォーム
    case CUSTOMER = 'customer'; // 汎用顧客プラットフォーム
    case TDBS = 'tdbs';         // TDBS独立システム

    /**
     * テナントの表示名を取得
     */
    public function label(): string
    {
        return match($this) {
            self::ELC => 'Elemental Cloud',
            self::TERAKONA => 'TERAKONA',
            self::CUSTOMER => 'Customer Platform',
            self::TDBS => 'TDBS',
        };
    }

    /**
     * テナントがアクティブかどうか
     */
    public function isActive(): bool
    {
        return config("tenants.{$this->value}.enabled", false);
    }
}
```

### 3.2 テナントコンテキストの管理

**新規ファイル:** `app/Services/TenantContext.php`

```php
<?php

namespace App\Services;

use App\Enums\Tenant;

class TenantContext
{
    private static ?Tenant $current = null;

    /**
     * 現在のテナントを設定
     */
    public static function set(Tenant $tenant): void
    {
        self::$current = $tenant;
    }

    /**
     * 現在のテナントを取得
     */
    public static function get(): ?Tenant
    {
        return self::$current;
    }

    /**
     * 現在のテナントIDを取得
     */
    public static function id(): ?string
    {
        return self::$current?->value;
    }

    /**
     * テナント固有の設定値を取得
     */
    public static function config(string $key, mixed $default = null): mixed
    {
        if (!self::$current) {
            return $default;
        }

        return config("tenants." . self::$current->value . ".{$key}", $default);
    }

    /**
     * コンテキストをリセット
     */
    public static function reset(): void
    {
        self::$current = null;
    }
}
```

### 3.3 テナント識別ミドルウェア

**新規ファイル:** `app/Http/Middleware/IdentifyTenant.php`

```php
<?php

namespace App\Http\Middleware;

use App\Enums\Tenant;
use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    /**
     * テナントを識別してコンテキストに設定
     */
    public function handle(Request $request, Closure $next, ?string $tenant = null)
    {
        // 1. ルートパラメータから識別
        if ($tenant) {
            $tenantEnum = Tenant::tryFrom($tenant);
            if ($tenantEnum) {
                TenantContext::set($tenantEnum);
            }
        }

        // 2. ヘッダーから識別（API用）
        elseif ($header = $request->header('X-Tenant-ID')) {
            $tenantEnum = Tenant::tryFrom($header);
            if ($tenantEnum) {
                TenantContext::set($tenantEnum);
            }
        }

        // 3. URLプレフィックスから識別
        elseif ($this->identifyFromPath($request)) {
            // 識別済み
        }

        return $next($request);
    }

    private function identifyFromPath(Request $request): bool
    {
        $path = $request->path();

        // /api/tdbs/* → TDBS
        if (str_starts_with($path, 'api/tdbs')) {
            TenantContext::set(Tenant::TDBS);
            return true;
        }

        // /api/terakona/* → TERAKONA
        if (str_starts_with($path, 'api/terakona')) {
            TenantContext::set(Tenant::TERAKONA);
            return true;
        }

        // /api/customer/* → CUSTOMER
        if (str_starts_with($path, 'api/customer')) {
            TenantContext::set(Tenant::CUSTOMER);
            return true;
        }

        return false;
    }
}
```

---

## 4. Phase 2: 設定層の抽象化

### 4.1 テナント設定ファイルの作成

**新規ファイル:** `config/tenants.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TERAKONA テナント設定
    |--------------------------------------------------------------------------
    */
    'terakona' => [
        'enabled' => true,
        'label' => 'TERAKONA',

        // Email 2FA設定
        'email_2fa' => [
            'enabled' => env('TERAKONA_ENABLE_EMAIL_2FA', true),
            'code_expire_minutes' => env('TERAKONA_EMAIL_2FA_CODE_EXPIRE', 15),
        ],

        // Email検証設定
        'email_verification' => [
            'enabled' => env('TERAKONA_ENABLE_EMAIL_VERIFICATION', true),
            'timeout_minutes' => env('TERAKONA_EMAIL_VERIFICATION_TIMEOUT', 15),
        ],

        // フロントエンドURL
        'frontend' => [
            'base_url' => env('BASE_FRONTEND_URL_TERAKONA'),
            'forget_password_url' => env('BASE_FRONTEND_URL_TERAKONA_FORGET_PASSWORD'),
            'verify_registration_url' => env('BASE_FRONTEND_URL_TERAKONA_VERIFY_REGISTRATION'),
            'customer_plans_url' => env('BASE_FRONTEND_URL_TERAKONA_CUSTOMER_PLANS'),
        ],

        // OAuth設定
        'oauth' => [
            'google' => [
                'client_id' => env('TERAKONA_GOOGLE_CLIENT_ID'),
                'client_secret' => env('TERAKONA_GOOGLE_CLIENT_SECRET'),
                'redirect_url' => env('TERAKONA_GOOGLE_REDIRECT_URL'),
            ],
            'line' => [
                'client_id' => env('TERAKONA_LINE_CLIENT_ID'),
                'client_secret' => env('TERAKONA_LINE_CLIENT_SECRET'),
                'redirect_url' => env('TERAKONA_LINE_REDIRECT_URL'),
                'login_redirect_url' => env('TERAKONA_LINE_LOGIN_REDIRECT_URL'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER テナント設定
    |--------------------------------------------------------------------------
    */
    'customer' => [
        'enabled' => true,
        'label' => 'Customer Platform',

        'email_2fa' => [
            'enabled' => env('CUSTOMER_ENABLE_EMAIL_2FA', true),
            'code_expire_minutes' => env('CUSTOMER_EMAIL_2FA_CODE_EXPIRE', 15),
        ],

        'email_verification' => [
            'enabled' => env('CUSTOMER_ENABLE_EMAIL_VERIFICATION', true),
            'timeout_minutes' => env('CUSTOMER_EMAIL_VERIFICATION_TIMEOUT', 15),
        ],

        'frontend' => [
            'base_url' => env('BASE_FRONTEND_URL_CUSTOMER'),
            'forget_password_url' => env('BASE_FRONTEND_URL_CUSTOMER_FORGET_PASSWORD'),
            'verify_registration_url' => env('BASE_FRONTEND_URL_CUSTOMER_VERIFY_REGISTRATION'),
            'customer_plans_url' => env('BASE_FRONTEND_URL_CUSTOMER_PLANS'),
        ],

        'oauth' => [
            'google' => [
                'client_id' => env('CUSTOMER_GOOGLE_CLIENT_ID'),
                'client_secret' => env('CUSTOMER_GOOGLE_CLIENT_SECRET'),
                'redirect_url' => env('CUSTOMER_GOOGLE_REDIRECT_URL'),
            ],
            'line' => [
                'client_id' => env('CUSTOMER_LINE_CLIENT_ID'),
                'client_secret' => env('CUSTOMER_LINE_CLIENT_SECRET'),
                'redirect_url' => env('CUSTOMER_LINE_REDIRECT_URL'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TDBS テナント設定
    |--------------------------------------------------------------------------
    */
    'tdbs' => [
        'enabled' => true,
        'label' => 'TDBS',

        // TDBSは管理者APIのみなので認証設定は限定的
        'database' => [
            'table_prefix' => 'tdbs_',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ELC（管理システム）テナント設定
    |--------------------------------------------------------------------------
    */
    'elc' => [
        'enabled' => true,
        'label' => 'Elemental Cloud Admin',
    ],
];
```

### 4.2 設定アクセスヘルパーの作成

**新規ファイル:** `app/Helpers/tenant.php`

```php
<?php

use App\Services\TenantContext;

if (!function_exists('tenant_config')) {
    /**
     * テナント固有の設定値を取得
     *
     * @param string $key ドット記法のキー（例: 'email_2fa.enabled'）
     * @param mixed $default デフォルト値
     * @return mixed
     */
    function tenant_config(string $key, mixed $default = null): mixed
    {
        return TenantContext::config($key, $default);
    }
}

if (!function_exists('tenant_id')) {
    /**
     * 現在のテナントIDを取得
     */
    function tenant_id(): ?string
    {
        return TenantContext::id();
    }
}

if (!function_exists('tenant')) {
    /**
     * 現在のテナントEnumを取得
     */
    function tenant(): ?\App\Enums\Tenant
    {
        return TenantContext::get();
    }
}
```

### 4.3 既存コードの修正例

**Before:**
```php
// app/Domain/Customer/Auth/RegisterUserUseCase.php
if (config('auth.terakona_email_verification.enabled')) {
    // ...
}
```

**After:**
```php
// app/Domain/Customer/Auth/RegisterUserUseCase.php
if (tenant_config('email_verification.enabled')) {
    // ...
}
```

---

## 5. Phase 3: ドメイン層の抽象化

### 5.1 テナント固有ロジックのインターフェース定義

**新規ファイル:** `app/Domain/Contracts/TenantAuthConfigInterface.php`

```php
<?php

namespace App\Domain\Contracts;

interface TenantAuthConfigInterface
{
    public function isEmail2FAEnabled(): bool;
    public function getEmail2FAExpireMinutes(): int;
    public function isEmailVerificationEnabled(): bool;
    public function getEmailVerificationTimeoutMinutes(): int;
    public function getFrontendBaseUrl(): string;
    public function getForgetPasswordUrl(): string;
    public function getVerifyRegistrationUrl(): string;
}
```

### 5.2 テナント固有実装

**新規ファイル:** `app/Domain/Tenants/TerakonaAuthConfig.php`

```php
<?php

namespace App\Domain\Tenants;

use App\Domain\Contracts\TenantAuthConfigInterface;

class TerakonaAuthConfig implements TenantAuthConfigInterface
{
    public function isEmail2FAEnabled(): bool
    {
        return config('tenants.terakona.email_2fa.enabled', true);
    }

    public function getEmail2FAExpireMinutes(): int
    {
        return config('tenants.terakona.email_2fa.code_expire_minutes', 15);
    }

    public function isEmailVerificationEnabled(): bool
    {
        return config('tenants.terakona.email_verification.enabled', true);
    }

    public function getEmailVerificationTimeoutMinutes(): int
    {
        return config('tenants.terakona.email_verification.timeout_minutes', 15);
    }

    public function getFrontendBaseUrl(): string
    {
        return config('tenants.terakona.frontend.base_url', '');
    }

    public function getForgetPasswordUrl(): string
    {
        return config('tenants.terakona.frontend.forget_password_url', '');
    }

    public function getVerifyRegistrationUrl(): string
    {
        return config('tenants.terakona.frontend.verify_registration_url', '');
    }
}
```

### 5.3 テナント設定ファクトリー

**新規ファイル:** `app/Domain/Tenants/TenantAuthConfigFactory.php`

```php
<?php

namespace App\Domain\Tenants;

use App\Domain\Contracts\TenantAuthConfigInterface;
use App\Enums\Tenant;
use App\Services\TenantContext;

class TenantAuthConfigFactory
{
    public static function make(?Tenant $tenant = null): TenantAuthConfigInterface
    {
        $tenant = $tenant ?? TenantContext::get();

        return match($tenant) {
            Tenant::TERAKONA => new TerakonaAuthConfig(),
            Tenant::CUSTOMER => new CustomerAuthConfig(),
            default => throw new \InvalidArgumentException("Unknown tenant: {$tenant?->value}"),
        };
    }
}
```

### 5.4 UseCaseでの使用例

**Before:**
```php
class RegisterUserUseCase
{
    public function execute(RegisterUserInput $input): void
    {
        // ハードコードされた設定参照
        if (config('auth.terakona_email_verification.enabled')) {
            $timeout = config('auth.terakona_email_verification.timeout', 15);
            // ...
        }
    }
}
```

**After:**
```php
class RegisterUserUseCase
{
    public function __construct(
        private TenantAuthConfigInterface $authConfig
    ) {}

    public function execute(RegisterUserInput $input): void
    {
        // テナント抽象化された設定参照
        if ($this->authConfig->isEmailVerificationEnabled()) {
            $timeout = $this->authConfig->getEmailVerificationTimeoutMinutes();
            // ...
        }
    }
}
```

---

## 6. Phase 4: ルーティング・アクション層の統一

### 6.1 ルート構造の整理

**目標構造:**
```
routes/
├── api.php                 # メインルート（共通）
└── api/
    ├── common.php          # 共通API
    ├── admin.php           # 管理API
    └── tenants/
        ├── terakona.php    # TERAKONA固有API
        ├── customer.php    # CUSTOMER固有API
        └── tdbs.php        # TDBS固有API（既存）
```

### 6.2 共通アクションの抽出

**Before:** 各テナントに重複したLoginAction

```
app/Docs/Actions/Api/Terakona/Auth/LoginAction.php
app/Docs/Actions/Api/Customer/Auth/LoginAction.php
```

**After:** 共通LoginAction + テナント固有設定

```
app/Http/Actions/Api/Auth/LoginAction.php  # 共通ロジック
```

```php
class LoginAction extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private TenantAuthConfigInterface $authConfig
    ) {}

    public function __invoke(LoginRequest $request)
    {
        // テナントに関係なく同じロジック
        // テナント固有の振る舞いはDI経由で注入
    }
}
```

---

## 7. Phase 5: テスト・検証・移行完了

### 7.1 テスト戦略

```php
// tests/Feature/MultiTenant/TenantContextTest.php
class TenantContextTest extends TestCase
{
    public function test_tenant_identified_from_url_prefix(): void
    {
        $response = $this->get('/api/terakona/auth/user');

        $this->assertEquals('terakona', tenant_id());
    }

    public function test_tenant_config_returns_correct_values(): void
    {
        TenantContext::set(Tenant::TERAKONA);

        $this->assertTrue(tenant_config('email_2fa.enabled'));
        $this->assertEquals(15, tenant_config('email_2fa.code_expire_minutes'));
    }
}
```

### 7.2 移行チェックリスト

#### Phase 1 完了条件
- [ ] `Tenant` Enum作成
- [ ] `TenantContext` サービス作成
- [ ] `IdentifyTenant` ミドルウェア作成
- [ ] ミドルウェアをKernelに登録

#### Phase 2 完了条件
- [ ] `config/tenants.php` 作成
- [ ] ヘルパー関数作成
- [ ] `.env` の既存変数は維持（後方互換性）
- [ ] 既存の `config('auth.terakona_...')` 参照を `tenant_config()` に置換

#### Phase 3 完了条件
- [ ] `TenantAuthConfigInterface` 作成
- [ ] 各テナント実装クラス作成
- [ ] UseCaseのDI修正
- [ ] ServiceProviderでのバインディング設定

#### Phase 4 完了条件
- [ ] ルートファイル構造整理
- [ ] 重複Actionの統合
- [ ] 既存APIの動作確認

#### Phase 5 完了条件
- [ ] ユニットテスト作成・パス
- [ ] 結合テスト作成・パス
- [ ] 既存機能のリグレッションテスト
- [ ] 本番環境デプロイ

---

## 8. 作業見積もり

| Phase | 作業内容 | 影響ファイル数 | 優先度 |
|-------|---------|--------------|--------|
| Phase 1 | 基盤構築 | ~5 新規 | **最優先** |
| Phase 2 | 設定層抽象化 | ~10 修正 | 高 |
| Phase 3 | ドメイン層抽象化 | ~50 修正 | 高 |
| Phase 4 | ルーティング統一 | ~100 修正 | 中 |
| Phase 5 | テスト・検証 | ~30 新規 | 高 |

---

## 9. リスクと対策

| リスク | 影響度 | 対策 |
|-------|-------|------|
| 既存APIの破壊的変更 | 高 | 後方互換性維持、段階的移行 |
| テナント識別の誤り | 高 | 十分なテストカバレッジ |
| パフォーマンス低下 | 中 | コンテキスト解決のキャッシュ |
| 設定の複雑化 | 中 | 明確なドキュメント整備 |

---

## 10. 次のアクション

1. **Phase 1 の実装開始**
   - `Tenant` Enum の作成
   - `TenantContext` サービスの作成
   - ミドルウェアの作成と登録

2. **既存コードの影響分析の深掘り**
   - `config('auth.terakona_...')` の使用箇所を全て特定
   - テナント固有ロジックの洗い出し

3. **チーム内レビュー**
   - この計画のレビューと承認
   - 懸念点・追加要件の収集
