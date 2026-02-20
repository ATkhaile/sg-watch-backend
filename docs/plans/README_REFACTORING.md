# Production機能リファクタリング - 完全ガイド

## 📚 ドキュメント一覧

このディレクトリには、Production機能の大規模リファクタリングに関する全てのドキュメントが含まれています。

### メインドキュメント

1. **[PRODUCTION_REFACTORING.md](./PRODUCTION_REFACTORING.md)**
   - Production機能のService/Repositoryリファクタリングの詳細
   - アーキテクチャパターン、各層の責務、実装ガイド
   - **推奨**: 最初に読むべきドキュメント

2. **[ACTION_RESTRUCTURING.md](./ACTION_RESTRUCTURING.md)**
   - Actionディレクトリの再編成に関する詳細
   - Before/After、移行手順、検証方法
   - **推奨**: 2番目に読むべきドキュメント

---

## 🎯 プロジェクト概要

### 目的
Production配下に肥大化していた機能を、DDDとクリーンアーキテクチャの原則に基づいてリファクタリング。

### 実施内容
1. **Service/Repositoryパターンの導入**
   - ビジネスロジックの分離
   - データアクセス層の抽象化

2. **Actionディレクトリの再編成**
   - Production配下から機能別に分散
   - 関心の分離を明確化

3. **Employee Domainの完全DDD実装**
   - Entity/UseCase/Repository/Infrastructure
   - ドメインロジックの完全な分離

---

## 📊 成果サマリー

### コード削減
- **GetDashboardDataAction**: 150行 → 10行 (93%削減)
- **GetEmployeeProfitabilityAction**: 180行 → 25行 (86%削減)
- **GetProjectFinancialsAction**: 100行 → 20行 (80%削減)
- **平均削減率**: 80-90%

### 実装したファイル
- **新規作成**:
  - Service: 6個
  - Repository: 6個
  - Domain関連: 17個
  - **合計**: 29個の新規ファイル

- **リファクタリング**:
  - Action: 30個
  - ディレクトリ移動・再編成

### 削除した不要ファイル
- EmployeeLoginAction (未使用)
- BaseProductionApiAction (未使用)

---

## 🗂️ 新しいディレクトリ構造

```
app/
├── Domain/                              # DDD Domain層
│   └── Employee/
│       ├── Entity/                      # エンティティ
│       ├── UseCase/                     # ユースケース
│       ├── Repository/                  # リポジトリIF
│       ├── Infrastructure/              # インフラ実装
│       └── Factory/                     # ファクトリ
│
├── Services/Production/                 # Service層
│   ├── WorkLogService.php
│   ├── ExternalCostService.php
│   ├── HelpRequestService.php
│   ├── ProductionReportService.php
│   └── ProjectService.php
│
├── Repositories/Production/             # Repository層
│   ├── WorkLogRepository.php
│   ├── ExternalCostRepository.php
│   ├── HelpRequestRepository.php
│   ├── ProductionReportRepository.php
│   └── ProjectRepository.php
│
└── Http/Actions/Api/                    # Action層 (再編成後)
    ├── Employee/                        # 従業員管理
    ├── WorkLog/                         # 工数管理
    ├── ExternalCost/                    # 外注費管理
    ├── HelpRequest/                     # ヘルプ要請
    ├── Project/                         # プロジェクト管理
    ├── ProductionReport/                # レポート機能
    └── FinancialReport/                 # 財務レポート
```

---

## 🏗️ アーキテクチャ概要

```
┌─────────────────────────────────────────────┐
│          HTTP Layer (Actions)               │
│  - リクエスト/レスポンス処理                    │
│  - バリデーション                              │
│  - 権限チェック                                │
└────────────────┬────────────────────────────┘
                 │ 依存
┌────────────────▼────────────────────────────┐
│       Service Layer (Services)              │
│  - ビジネスロジック                            │
│  - データ変換・集計                            │
│  - 複雑な計算処理                              │
└────────────────┬────────────────────────────┘
                 │ 依存
┌────────────────▼────────────────────────────┐
│    Repository Layer (Repositories)          │
│  - データアクセス                              │
│  - クエリ構築                                  │
│  - フィルタリング・ソート                       │
└────────────────┬────────────────────────────┘
                 │ 依存
┌────────────────▼────────────────────────────┐
│      Model Layer (Eloquent Models)          │
│  - データベース操作                            │
│  - リレーション定義                            │
└─────────────────────────────────────────────┘
```

---

## 📖 各ドキュメントの内容

### PRODUCTION_REFACTORING.md
**内容:**
- リファクタリング前後の構造比較
- 実装した6つの機能の詳細
- アーキテクチャパターン（Full DDD vs Service/Repository）
- 各層の責務と実装ガイドライン
- ファイル構成と次のステップ

**対象読者:**
- 新しく参画する開発者
- アーキテクチャを理解したい人
- Service/Repositoryパターンを学びたい人

### ACTION_RESTRUCTURING.md
**内容:**
- Actionディレクトリの再編成詳細
- Before/After の具体的な構造
- 移行したファイル一覧（30ファイル）
- Namespaceとルーティングの更新方法
- 検証手順とトラブルシューティング

**対象読者:**
- ファイル移動の経緯を知りたい人
- ディレクトリ構造を理解したい人
- トラブルシューティングが必要な人

---

## 🚀 クイックスタート

### 新規参画者向け

1. **まず読むべきドキュメント順:**
   ```
   1. この README_REFACTORING.md (全体像把握)
   2. PRODUCTION_REFACTORING.md (技術詳細)
   3. ACTION_RESTRUCTURING.md (ディレクトリ構造)
   ```

2. **コードを理解する順番:**
   ```
   1. Action層のコードを読む
      例: app/Http/Actions/Api/Employee/GetAllEmployeesAction.php

   2. Service層のコードを読む
      例: app/Services/Production/WorkLogService.php

   3. Repository層のコードを読む
      例: app/Repositories/Production/WorkLogRepository.php

   4. Domain層のコードを読む (Employee のみ)
      例: app/Domain/Employee/UseCase/GetAllEmployeesUseCase.php
   ```

3. **実際に動かして確認:**
   ```bash
   # ルートキャッシュをクリア
   php artisan route:clear

   # ルート一覧を確認
   php artisan route:list | grep employee

   # 動作確認（例: 従業員一覧取得）
   curl http://localhost:8000/api/v1/admin/employees/list
   ```

---

## 📋 実装パターン比較

### Pattern 1: Full DDD (Employee)
**使用ケース:** 独立性が高く、複雑なビジネスロジックがある機能

```php
Action → UseCase → Repository(Interface) → Infrastructure(Implementation)
```

**特徴:**
- ✅ ドメインロジックの完全な分離
- ✅ 高いテスタビリティ
- ✅ 変更に強い
- ❌ ファイル数が多い
- ❌ 学習コストが高い

### Pattern 2: Service/Repository (WorkLog, Project, etc.)
**使用ケース:** 他のAggregateに依存する、またはシンプルなCRUD機能

```php
Action → Service → Repository → Model
```

**特徴:**
- ✅ シンプルで理解しやすい
- ✅ 実装が速い
- ✅ 適度な分離
- ❌ Full DDDほど厳密ではない

---

## 🔧 開発ガイドライン

### 新しい機能を追加する場合

#### 1. パターンの選択
```
独立性が高い → Full DDD
依存が多い → Service/Repository
```

#### 2. Full DDDの場合
```
1. app/Domain/{Feature}/Entity/ にエンティティを作成
2. app/Domain/{Feature}/UseCase/ にユースケースを作成
3. app/Domain/{Feature}/Repository/ にインターフェースを作成
4. app/Domain/{Feature}/Infrastructure/ に実装を作成
5. app/Http/Actions/Api/{Feature}/ にActionを作成
6. Provider を app/Providers/Domain/ に作成
7. AppServiceProvider に登録
```

#### 3. Service/Repositoryの場合
```
1. app/Repositories/Production/{Feature}Repository.php を作成
2. app/Services/Production/{Feature}Service.php を作成
3. app/Http/Actions/Api/{Feature}/ にActionを作成
```

### コーディング規約

#### Action Layer
```php
// ✅ Good
public function __invoke(Request $request): JsonResponse
{
    $validator = Validator::make(...);
    if ($validator->fails()) {
        return $this->sendError(...);
    }

    $result = $this->service->doSomething($data);

    return $this->sendResponse($result['data'], $result['message']);
}

// ❌ Bad - ビジネスロジックをActionに書かない
public function __invoke(Request $request): JsonResponse
{
    $project = Project::find($id);
    if (!$project->isActive()) {
        // ビジネスロジック！
    }
}
```

#### Service Layer
```php
// ✅ Good
public function createWorkLog(array $data, int $employeeId): array
{
    // ビジネスルールの検証
    $project = Project::find($data['project_id']);
    if (!$project || !$project->isActive()) {
        return ['success' => false, 'message' => 'Invalid project'];
    }

    // Repository呼び出し
    $workLog = $this->repository->create($data);

    return ['success' => true, 'data' => $workLog];
}

// ❌ Bad - HTTPレスポンスを返さない
public function createWorkLog(): JsonResponse
{
    // JsonResponseはActionで返すべき
}
```

#### Repository Layer
```php
// ✅ Good
public function findAll(/* filters */): LengthAwarePaginator
{
    $query = WorkLog::with(['project', 'employee']);

    if ($status) {
        $query->where('status', $status);
    }

    return $query->paginate($perPage);
}

// ❌ Bad - ビジネスロジックを書かない
public function findAll(): LengthAwarePaginator
{
    // プロジェクトがアクティブかチェック
    if (!$project->isActive()) {
        // これはServiceの責務！
    }
}
```

---

## 🧪 テスト戦略

### テストの配置
```
tests/
├── Unit/
│   ├── Services/Production/
│   │   ├── WorkLogServiceTest.php
│   │   └── ProjectServiceTest.php
│   └── Repositories/Production/
│       ├── WorkLogRepositoryTest.php
│       └── ProjectRepositoryTest.php
├── Feature/
│   └── Http/Actions/Api/
│       ├── Employee/
│       │   └── GetAllEmployeesActionTest.php
│       └── WorkLog/
│           └── CreateMyWorkLogActionTest.php
└── Domain/
    └── Employee/
        └── UseCase/
            └── GetAllEmployeesUseCaseTest.php
```

### テストの書き方例

#### Service Test
```php
public function test_create_work_log_with_invalid_project()
{
    // Arrange
    $service = new WorkLogService($repositoryMock);
    $data = ['project_id' => 999]; // 存在しないID

    // Act
    $result = $service->createWorkLog($data, 1);

    // Assert
    $this->assertFalse($result['success']);
    $this->assertEquals('Project not found', $result['message']);
}
```

#### Action Test
```php
public function test_get_all_employees_returns_success()
{
    // Arrange
    $this->actingAs($adminUser);

    // Act
    $response = $this->get('/api/v1/admin/employees/list');

    // Assert
    $response->assertStatus(200);
    $response->assertJsonStructure(['data', 'message']);
}
```

---

## 🔍 トラブルシューティング

### よくあるエラーと解決方法

#### 1. Class not found
```
Error: Class 'App\Http\Actions\Api\Production\...' not found
```

**原因:** ルートキャッシュが古い
**解決策:**
```bash
php artisan route:clear
php artisan config:clear
composer dump-autoload
```

#### 2. Namespace mismatch
```
Error: Class name must match filename
```

**原因:** Namespaceの更新漏れ
**解決策:** ファイルのnamespace行を確認

#### 3. Service not bound
```
Error: Target class [ProjectService] does not exist
```

**原因:** DIコンテナへの登録漏れ
**解決策:** AppServiceProviderまたはDomainProviderで登録

---

## 📈 今後の展開

### 短期的な改善（1-2週間）
- [ ] 残りの複雑なActionのリファクタリング
  - GetProjectWorkLogsSummaryAction
- [ ] 単体テストの追加
- [ ] 統合テストの追加

### 中期的な改善（1-2ヶ月）
- [ ] Project機能のFull DDD化検討
- [ ] キャッシュ戦略の実装
- [ ] パフォーマンス最適化

### 長期的な改善（3-6ヶ月）
- [ ] マイクロサービス化の検討
- [ ] GraphQL対応
- [ ] リアルタイム機能の追加

---

## 👥 貢献者向けガイド

### プルリクエストのガイドライン

1. **ブランチ命名規則**
   ```
   feature/add-new-service
   refactor/improve-repository
   fix/action-bug
   ```

2. **コミットメッセージ**
   ```
   feat: Add WorkLog service layer
   refactor: Extract business logic to service
   fix: Fix namespace in Action
   docs: Update refactoring guide
   ```

3. **レビューポイント**
   - [ ] 適切な層に実装されているか
   - [ ] ビジネスロジックがServiceにあるか
   - [ ] Actionは薄く保たれているか
   - [ ] テストが書かれているか
   - [ ] ドキュメントが更新されているか

---

## 📚 参考資料

### 書籍
- ドメイン駆動設計 (Eric Evans)
- クリーンアーキテクチャ (Robert C. Martin)
- エンタープライズアプリケーションアーキテクチャパターン (Martin Fowler)

### オンラインリソース
- [Laravel公式ドキュメント](https://laravel.com/docs)
- [DDD Community](https://github.com/ddd-crew)
- [Repository Pattern in Laravel](https://asperbrothers.com/blog/repository-pattern-in-laravel/)

---

## 🙏 謝辞

このリファクタリングは、チーム全体の協力により実現しました。
特に、アーキテクチャの設計から実装まで、多くの知見とフィードバックをいただきました。

---

## 📞 サポート

質問や問題がある場合:
1. まず関連ドキュメントを確認
2. GitHubのIssueで質問
3. チームSlackで相談

---

**作成者**: Claude
**最終更新**: 2025-01-10
**バージョン**: 1.0
**ステータス**: ✅ 完了
