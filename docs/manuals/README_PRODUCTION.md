# 生産管理システム

従業員の各プロジェクトに対する生産性を把握することを目的とした生産管理システムです。

## システム構成

- **バックエンド**: Laravel 11 (PHP 8.2+)
- **フロントエンド**: Next.js 15 + TypeScript + Tailwind CSS + shadcn/ui
- **データベース**: MySQL/MariaDB
- **認証**: JWT Token

## 機能概要

### 権限別機能

#### 従業員 (Member)
- ✅ 自分の工数入力・編集・削除
- ✅ 個人ダッシュボード
- ✅ Help要請の作成・閲覧
- ✅ 自分の工数履歴確認

#### マネージャー (Manager)
- ✅ プロジェクト管理（作成・編集・削除）
- ✅ チーム工数管理・承認
- ✅ 外注費管理
- ✅ Help要請のアサイン・解決
- ✅ 生産性レポート
- ✅ ダッシュボード（管理者向け）

#### 管理者 (Admin)
- ✅ 全機能へのアクセス
- ✅ 従業員管理
- ✅ 給与・コスト管理
- ✅ 財務レポート
- ✅ CSV出力

### 主要機能

1. **プロジェクト管理**
   - プロジェクトの作成・編集・削除
   - 進捗管理（工数ベース・マイルストーンベース）
   - 収益性分析（粗利・粗利率）
   - メンバー配属管理

2. **工数管理**
   - 従業員による直接入力（1分刻み）
   - 承認ワークフロー
   - カテゴリ別分類（開発・デザイン・会議・テスト・運用・その他）
   - 残業フラグ・請求対象フラグ

3. **従業員管理**
   - 従業員マスタ管理
   - 給与履歴管理
   - 生産性指標算出
   - 権限管理

4. **外注費管理**
   - 外注費明細の登録・管理
   - 支払い状況管理
   - 添付ファイル対応

5. **Help要請管理**
   - 問題・課題の管理
   - 重要度・ブロッカー設定
   - SLA管理

6. **レポート・分析**
   - ダッシュボード（KPI表示）
   - 生産性レポート
   - 財務レポート
   - CSV出力（工数・P/L・外注費・従業員コスト・Help）

## セットアップ手順

### 1. Laravel (API) セットアップ

```bash
# 依存関係のインストール
composer install

# 環境設定ファイルのコピー
cp .env.example .env

# データベース設定 (.envファイルを編集)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=team_manage
DB_USERNAME=your_username
DB_PASSWORD=your_password

# アプリケーションキーの生成
php artisan key:generate

# JWTシークレットキーの生成
php artisan jwt:secret

# マイグレーション実行
php artisan migrate

# 生産管理用テーブルのマイグレーション
php artisan migrate --path=database/migrations/2025_08_20_000001_create_production_management_tables.php

# シーダー実行
php artisan db:seed --class=ProductionManagementSeeder

# サーバー起動
php artisan serve
```

### 2. Next.js (Frontend) セットアップ

```bash
# フロントエンドディレクトリに移動
cd frontend

# 依存関係のインストール
npm install

# 環境変数設定
echo "NEXT_PUBLIC_API_URL=http://localhost:8000/api" > .env.local

# 開発サーバー起動
npm run dev
```

## API エンドポイント

### 管理者向けAPI

```
GET    /api/projects/list                    - プロジェクト一覧
POST   /api/projects/create                  - プロジェクト作成
GET    /api/projects/{id}                    - プロジェクト詳細
PUT    /api/projects/{id}                    - プロジェクト更新
DELETE /api/projects/{id}                    - プロジェクト削除
GET    /api/projects/{id}/financials         - プロジェクト財務情報

GET    /api/employees/list                   - 従業員一覧
POST   /api/employees/create                 - 従業員作成
GET    /api/employees/{id}                   - 従業員詳細
PUT    /api/employees/{id}                   - 従業員更新
DELETE /api/employees/{id}                   - 従業員削除

GET    /api/work-logs/list                   - 工数ログ一覧
POST   /api/work-logs/create                 - 工数ログ作成
POST   /api/work-logs/bulk                   - 工数ログ一括作成
POST   /api/work-logs/{id}/approve           - 工数ログ承認

GET    /api/reports/dashboard                - ダッシュボードデータ
GET    /api/reports/export/work-logs         - 工数CSV出力
GET    /api/reports/export/project-pl        - プロジェクトP/L出力
```

### 従業員向けAPI

```
GET    /api/my/work-logs/list                - 自分の工数一覧
POST   /api/my/work-logs/create              - 自分の工数登録
PUT    /api/my/work-logs/{id}                - 自分の工数更新
DELETE /api/my/work-logs/{id}                - 自分の工数削除

GET    /api/my/dashboard                     - 個人ダッシュボード
GET    /api/my/profile                       - 個人プロフィール
```

## データ設計の特徴

### 主要テーブル

1. **projects** - プロジェクト情報
2. **employees** - 従業員情報
3. **employee_compensations** - 給与履歴
4. **project_members** - プロジェクト配属
5. **work_logs** - 工数ログ（1分刻み）
6. **external_costs** - 外注費明細
7. **help_requests** - Help要請

### 利益計算ロジック

```
売上 = projects.contract_amount
外注費 = Σ external_costs.amount
自社工数原価 = Σ(work_logs.minutes × 従業員minute_cost)
minute_cost = (salary_monthly × (1 + overhead_rate)) / standard_hours_per_month / 60
粗利 = 売上 - 外注費 - 自社工数原価
粗利率 = 粗利 / 売上 × 100
```

## 権限設計

### ロール
- `production_admin` - 生産管理管理者
- `production_manager` - 生産管理マネージャー  
- `production_member` - 生産管理メンバー

### 主要権限
- `worklog.view.own` - 自分の工数閲覧
- `worklog.create.own` - 自分の工数入力
- `worklog.view.all` - 全工数閲覧
- `worklog.approve` - 工数承認
- `project.view` - プロジェクト閲覧
- `project.create` - プロジェクト作成
- `employee.compensation` - 給与情報管理
- `report.financial` - 財務レポート

## 画面構成

### 管理者・マネージャー向け
- `/dashboard` - 管理ダッシュボード
- `/projects` - プロジェクト管理
- `/employees` - 従業員管理
- `/work-logs` - 工数管理
- `/external-costs` - 外注費管理
- `/help-requests` - Help要請管理
- `/reports` - レポート

### 従業員向け
- `/my/dashboard` - 個人ダッシュボード
- `/my/work-logs` - 工数入力
- `/my/help-requests` - Help要請

## CSV出力フォーマット

### 工数明細
```
project_code,employee_code,employee_name,work_date,started_at,ended_at,duration_minutes,duration_hhmm,category,task_title,is_billable
```

### プロジェクトP/L
```
project_code,project_name,client_name,revenue,external_cost,labor_cost,gross_profit,gross_margin
```

### 従業員原価寄与
```
employee_code,employee_name,project_code,minutes,hours,minute_cost,amount
```

## 実装済み機能

### ✅ 完成済み画面
- [x] **ダッシュボード** (`/dashboard`) - KPI表示、プロジェクト進捗、収益性分析
- [x] **工数入力** (`/my/work-logs`) - 従業員による直接工数入力・編集
- [x] **プロジェクト管理** (`/projects`) - プロジェクト一覧・詳細・作成・編集
- [x] **従業員管理** (`/employees`) - 従業員マスタ管理・権限設定
- [x] **外注費管理** (`/external-costs`) - 外注費登録・支払い管理
- [x] **Help要請管理** (`/help-requests`) - Kanbanボード・リスト表示

### ✅ 実装済み機能
- [x] レスポンシブデザイン（PC・タブレット対応）
- [x] 権限ベースのUI制御
- [x] リアルタイム進捗計算
- [x] フィルター・検索機能
- [x] データバリデーション
- [x] エラーハンドリング

## 今後の拡張予定

- [ ] CSV出力機能の実装
- [ ] 認証機能の実装
- [ ] 通知機能の実装（WebSocket + メール/Slack）
- [ ] ファイルアップロード機能
- [ ] 詳細レポート画面
- [ ] モバイルアプリ対応
- [ ] 多言語対応
- [ ] ダークモード対応

## 技術的な改善点

### パフォーマンス
- work_logsテーブルにインデックス追加済み
- 集計クエリの最適化
- ページネーション対応

### セキュリティ
- 権限ベースのアクセス制御
- CSRFトークン
- SQLインジェクション対策
- XSS対策

### 可用性
- エラーハンドリング
- ログ出力
- バックアップ設計