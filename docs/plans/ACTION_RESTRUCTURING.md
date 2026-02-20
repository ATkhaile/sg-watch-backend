# Actionディレクトリ再編成ドキュメント

## 📋 概要

Production配下に集約されていたActionを、機能ごとに独立したディレクトリに再編成しました。
これにより、関心の分離がより明確になり、保守性が向上します。

**実施日**: 2025-01-10
**対象**: `app/Http/Actions/Api/Production/` 配下の全Action

---

## 🔄 変更内容

### Before (変更前)
```
app/Http/Actions/Api/Production/
├── Auth/
│   └── EmployeeLoginAction.php          # 削除済み（未使用）
├── Employees/
│   ├── GetAllEmployeesAction.php
│   ├── CreateEmployeeAction.php
│   ├── InviteEmployeeAction.php
│   ├── GetMyProfileAction.php
│   └── GetEmployeeCharacterAnalysisAction.php
├── MyWorkLogs/
│   ├── CreateMyWorkLogAction.php
│   ├── GetMyWorkLogsAction.php
│   ├── UpdateMyWorkLogAction.php
│   └── DeleteMyWorkLogAction.php
├── ExternalCosts/
│   ├── CreateExternalCostAction.php
│   ├── GetAllExternalCostsAction.php
│   ├── UpdateExternalCostAction.php
│   └── DeleteExternalCostAction.php
├── HelpRequests/
│   ├── CreateHelpRequestAction.php
│   └── GetAllHelpRequestsAction.php
├── Projects/
│   ├── GetAllProjectsAction.php
│   ├── CreateProjectAction.php
│   ├── UpdateProjectAction.php
│   ├── GetProjectDetailAction.php
│   ├── DeleteProjectAction.php
│   ├── GetProjectFinancialsAction.php
│   └── GetProjectWorkLogsSummaryAction.php
├── Reports/
│   ├── GetDashboardDataAction.php
│   ├── GetEmployeeProfitabilityAction.php
│   ├── GetEmployeeDetailAction.php
│   └── GenerateMonthlyReportPdfAction.php
├── FinancialReports/
│   └── GetFinancialReportsAction.php
├── GetCurrentUserAction.php
├── UpdateProjectNotesAction.php
└── BaseProductionApiAction.php          # 削除済み（未使用）
```

### After (変更後)
```
app/Http/Actions/Api/
├── Employee/                             # Production/Employees → Employee
│   ├── GetAllEmployeesAction.php
│   ├── CreateEmployeeAction.php
│   ├── InviteEmployeeAction.php
│   ├── GetMyProfileAction.php
│   ├── GetEmployeeCharacterAnalysisAction.php
│   └── GetCurrentUserAction.php         # Production/ → Employee/
├── WorkLog/                              # Production/MyWorkLogs → WorkLog
│   ├── CreateMyWorkLogAction.php
│   ├── GetMyWorkLogsAction.php
│   ├── UpdateMyWorkLogAction.php
│   └── DeleteMyWorkLogAction.php
├── ExternalCost/                         # Production/ExternalCosts → ExternalCost
│   ├── CreateExternalCostAction.php
│   ├── GetAllExternalCostsAction.php
│   ├── UpdateExternalCostAction.php
│   └── DeleteExternalCostAction.php
├── HelpRequest/                          # Production/HelpRequests → HelpRequest
│   ├── CreateHelpRequestAction.php
│   └── GetAllHelpRequestsAction.php
├── Project/                              # Production/Projects → Project
│   ├── GetAllProjectsAction.php
│   ├── CreateProjectAction.php
│   ├── UpdateProjectAction.php
│   ├── GetProjectDetailAction.php
│   ├── DeleteProjectAction.php
│   ├── GetProjectFinancialsAction.php
│   ├── GetProjectWorkLogsSummaryAction.php
│   └── UpdateProjectNotesAction.php     # Production/ → Project/
├── ProductionReport/                     # Production/Reports → ProductionReport
│   ├── GetDashboardDataAction.php
│   ├── GetEmployeeProfitabilityAction.php
│   ├── GetEmployeeDetailAction.php
│   └── GenerateMonthlyReportPdfAction.php
└── FinancialReport/                      # Production/FinancialReports → FinancialReport
    └── GetFinancialReportsAction.php
```

---

## 📝 変更詳細

### 1. ディレクトリ名の変更

| Before | After | 理由 |
|--------|-------|------|
| `Production/Employees/` | `Employee/` | 単数形で統一、Production prefix削除 |
| `Production/MyWorkLogs/` | `WorkLog/` | "My"プレフィックス削除、単数形に |
| `Production/ExternalCosts/` | `ExternalCost/` | 単数形に統一 |
| `Production/HelpRequests/` | `HelpRequest/` | 単数形に統一 |
| `Production/Projects/` | `Project/` | Production prefix削除 |
| `Production/Reports/` | `ProductionReport/` | 他のReportと区別するため |
| `Production/FinancialReports/` | `FinancialReport/` | 単数形に統一 |

### 2. Namespace の更新

全てのActionファイルのnamespaceを以下のように更新：

```php
// Before
namespace App\Http\Actions\Api\Production\Employees;

// After
namespace App\Http\Actions\Api\Employee;
```

### 3. ルーティングの更新

`routes/api.php` の全ての Production 参照を更新：

```php
// Before
Route::get('list', \App\Http\Actions\Api\Production\Employees\GetAllEmployeesAction::class);

// After
Route::get('list', \App\Http\Actions\Api\Employee\GetAllEmployeesAction::class);
```

---

## 🎯 移行したファイル一覧

### Employee (7ファイル)
- ✅ GetAllEmployeesAction.php
- ✅ CreateEmployeeAction.php
- ✅ InviteEmployeeAction.php
- ✅ GetMyProfileAction.php
- ✅ GetEmployeeCharacterAnalysisAction.php
- ✅ GetCurrentUserAction.php (Production/ から移動)

### WorkLog (4ファイル)
- ✅ CreateMyWorkLogAction.php
- ✅ GetMyWorkLogsAction.php
- ✅ UpdateMyWorkLogAction.php
- ✅ DeleteMyWorkLogAction.php

### ExternalCost (4ファイル)
- ✅ CreateExternalCostAction.php
- ✅ GetAllExternalCostsAction.php
- ✅ UpdateExternalCostAction.php
- ✅ DeleteExternalCostAction.php

### HelpRequest (2ファイル)
- ✅ CreateHelpRequestAction.php
- ✅ GetAllHelpRequestsAction.php

### Project (8ファイル)
- ✅ GetAllProjectsAction.php
- ✅ CreateProjectAction.php
- ✅ UpdateProjectAction.php
- ✅ GetProjectDetailAction.php
- ✅ DeleteProjectAction.php
- ✅ GetProjectFinancialsAction.php
- ✅ GetProjectWorkLogsSummaryAction.php
- ✅ UpdateProjectNotesAction.php (Production/ から移動)

### ProductionReport (4ファイル)
- ✅ GetDashboardDataAction.php
- ✅ GetEmployeeProfitabilityAction.php
- ✅ GetEmployeeDetailAction.php
- ✅ GenerateMonthlyReportPdfAction.php

### FinancialReport (1ファイル)
- ✅ GetFinancialReportsAction.php

**合計: 30ファイル移行完了**

---

## 🗑️ 削除したファイル

### 未使用ファイル
- ❌ `Production/Auth/EmployeeLoginAction.php` - UIで未使用
- ❌ `Production/BaseProductionApiAction.php` - 使用箇所なし

### 空ディレクトリ
- ❌ `Production/Auth/`
- ❌ `Production/Employees/`
- ❌ `Production/MyWorkLogs/`
- ❌ `Production/ExternalCosts/`
- ❌ `Production/HelpRequests/`
- ❌ `Production/Projects/`
- ❌ `Production/Reports/`
- ❌ `Production/FinancialReports/`
- ❌ `Production/` (親ディレクトリ)

---

## ✅ 検証手順

### 1. ファイル移動の確認
```bash
# 新しいディレクトリ構造の確認
ls -d app/Http/Actions/Api/{Employee,WorkLog,ExternalCost,HelpRequest,Project,ProductionReport,FinancialReport}/

# 各ディレクトリのファイル数確認
find app/Http/Actions/Api/Employee -name "*.php" | wc -l        # 7
find app/Http/Actions/Api/WorkLog -name "*.php" | wc -l         # 4
find app/Http/Actions/Api/ExternalCost -name "*.php" | wc -l    # 4
find app/Http/Actions/Api/HelpRequest -name "*.php" | wc -l     # 2
find app/Http/Actions/Api/Project -name "*.php" | wc -l         # 8
find app/Http/Actions/Api/ProductionReport -name "*.php" | wc -l # 4
find app/Http/Actions/Api/FinancialReport -name "*.php" | wc -l  # 1
```

### 2. Namespace の確認
```bash
# 各ファイルのnamespaceが正しいか確認
grep -h "^namespace" app/Http/Actions/Api/Employee/*.php | sort -u
grep -h "^namespace" app/Http/Actions/Api/WorkLog/*.php | sort -u
grep -h "^namespace" app/Http/Actions/Api/Project/*.php | sort -u
```

### 3. ルーティングの確認
```bash
# routes/api.php に古い Production パスが残っていないか確認
grep "Production\\\\" routes/api.php
# 何も出力されなければOK
```

### 4. アプリケーションの動作確認
```bash
# Laravelのルートキャッシュをクリア
php artisan route:clear

# ルート一覧を確認
php artisan route:list | grep -E "(employee|work-log|external-cost|help-request|project|report)"
```

---

## 🚀 影響範囲

### 変更が必要なファイル
1. ✅ `routes/api.php` - ルーティング定義の更新
2. ✅ 全Actionファイル - Namespace の更新

### 変更不要なファイル
- ❌ Service層 - Actionから呼ばれるのみ
- ❌ Repository層 - Actionから呼ばれるのみ
- ❌ Model層 - 変更なし
- ❌ フロントエンド - APIエンドポイントのパスは変わらない

---

## 📚 関連ドキュメント

- [Production機能リファクタリングドキュメント](./PRODUCTION_REFACTORING.md)
- [Service/Repository実装ガイド](./SERVICE_REPOSITORY_GUIDE.md)

---

## 🔍 トラブルシューティング

### エラー: Class not found
```
Error: Class 'App\Http\Actions\Api\Production\Employees\GetAllEmployeesAction' not found
```

**原因**: ルートキャッシュが古い
**解決策**:
```bash
php artisan route:clear
php artisan config:clear
composer dump-autoload
```

### エラー: Namespace mismatch
```
Error: Class name must match filename
```

**原因**: Namespaceの更新漏れ
**解決策**: 該当ファイルのnamespaceを確認・修正

---

## 📊 統計情報

| 項目 | Before | After | 差分 |
|------|--------|-------|------|
| ディレクトリ数 | 1 (Production) | 7 (機能別) | +6 |
| Actionファイル数 | 30 | 30 | 0 |
| 削除ファイル数 | - | 2 | -2 |
| 空ディレクトリ削除数 | - | 9 | -9 |

---

## ✨ メリット

### 1. 関心の分離
- 各機能が独立したディレクトリに
- Production という曖昧なくくりから解放

### 2. 可読性向上
- ディレクトリ名から機能が明確
- ファイル検索が容易

### 3. 保守性向上
- 機能ごとの変更が明確
- 影響範囲の把握が容易

### 4. 拡張性
- 新機能の追加が容易
- 機能の移動・統合がしやすい

---

## 🎓 学習ポイント

### Laravelのディレクトリ構成ベストプラクティス
1. **機能ごとにディレクトリを分ける**
   - ✅ `Employee/`, `Project/`, `WorkLog/`
   - ❌ `Production/` (抽象的すぎる)

2. **ディレクトリ名は単数形**
   - ✅ `Employee/`, `Project/`
   - ❌ `Employees/`, `Projects/`

3. **意味のあるグルーピング**
   - ✅ 機能ドメインで分ける
   - ❌ 技術的なレイヤーだけで分ける

---

**作成者**: Claude
**最終更新**: 2025-01-10
**バージョン**: 1.0
