<?php

namespace Tests\Feature\ExternalCost;

use App\Enums\Role;
use App\Models\User;
use App\Models\Project;
use App\Models\ExternalCost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExternalCostCrudTest extends TestCase
{
    use RefreshDatabase;

    private string $apiPrefix = '/api/v1';
    private ?string $adminToken = null;
    private User $adminUser;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        // 管理者ユーザーを作成
        $this->adminUser = User::create([
            'user_id' => 'admin-001',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('AdminPass123'),
            'role_id' => Role::ADMIN,
        ]);

        // テスト用プロジェクトを作成
        $this->project = Project::create([
            'code' => 'PRJ-TEST',
            'name' => 'テストプロジェクト',
            'client_name' => 'テストクライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 1000,
            'contract_amount' => 10000000,
            'status' => 'active',
            'progress_method' => 'hours_based',
        ]);

        // 管理者でログイン
        $loginResponse = $this->postJson("{$this->apiPrefix}/login", [
            'email' => 'admin@example.com',
            'password' => 'AdminPass123',
        ]);

        $this->adminToken = $loginResponse->json('token');
    }

    /**
     * 外注費を作成できる
     */
    public function test_can_create_external_cost(): void
    {
        $data = [
            'project_id' => $this->project->id,
            'vendor_name' => 'テスト業者',
            'category' => 'dev',
            'amount' => 500000,
            'tax_excluded' => true,
            'invoice_date' => '2024-06-01',
            'memo' => 'テストメモ',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/external-costs/create", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('external_costs', [
            'project_id' => $this->project->id,
            'vendor_name' => 'テスト業者',
            'category' => 'dev',
            'amount' => 500000,
        ]);
    }

    /**
     * 外注費を更新できる
     */
    public function test_can_update_external_cost(): void
    {
        $externalCost = ExternalCost::create([
            'project_id' => $this->project->id,
            'vendor_name' => '元の業者名',
            'category' => 'dev',
            'amount' => 300000,
            'invoice_date' => '2024-05-01',
        ]);

        $updateData = [
            'project_id' => $this->project->id,
            'vendor_name' => '更新後の業者名',
            'category' => 'design',
            'amount' => 400000,
            'invoice_date' => '2024-05-15',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("{$this->apiPrefix}/external-costs/{$externalCost->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('external_costs', [
            'id' => $externalCost->id,
            'vendor_name' => '更新後の業者名',
            'category' => 'design',
            'amount' => 400000,
        ]);
    }

    /**
     * 外注費を削除できる（ソフトデリート）
     */
    public function test_can_delete_external_cost(): void
    {
        $externalCost = ExternalCost::create([
            'project_id' => $this->project->id,
            'vendor_name' => '削除対象業者',
            'category' => 'qa',
            'amount' => 200000,
            'invoice_date' => '2024-04-01',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("{$this->apiPrefix}/external-costs/{$externalCost->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('external_costs', [
            'id' => $externalCost->id,
        ]);
    }

    /**
     * 外注費一覧を取得できる
     */
    public function test_can_get_external_costs_list(): void
    {
        // テストデータを作成
        ExternalCost::create([
            'project_id' => $this->project->id,
            'vendor_name' => '業者A',
            'category' => 'dev',
            'amount' => 100000,
            'invoice_date' => '2024-03-01',
        ]);

        ExternalCost::create([
            'project_id' => $this->project->id,
            'vendor_name' => '業者B',
            'category' => 'design',
            'amount' => 200000,
            'invoice_date' => '2024-03-15',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("{$this->apiPrefix}/external-costs/list");

        $response->assertStatus(200);
    }

    /**
     * 存在しない外注費を更新しようとすると404
     */
    public function test_update_nonexistent_external_cost_returns_404(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("{$this->apiPrefix}/external-costs/99999", [
                'project_id' => $this->project->id,
                'vendor_name' => 'テスト',
                'category' => 'dev',
                'amount' => 100000,
                'invoice_date' => '2024-01-01',
            ]);

        $response->assertStatus(404);
    }

    /**
     * 存在しない外注費を削除しようとすると404
     */
    public function test_delete_nonexistent_external_cost_returns_404(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("{$this->apiPrefix}/external-costs/99999");

        $response->assertStatus(404);
    }

    /**
     * バリデーションエラー: 必須項目なしで作成できない
     */
    public function test_cannot_create_external_cost_without_required_fields(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/external-costs/create", [
                'vendor_name' => 'テスト',
            ]);

        $response->assertStatus(422);
    }

    /**
     * バリデーションエラー: 存在しないプロジェクトで作成できない
     */
    public function test_cannot_create_external_cost_with_invalid_project(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/external-costs/create", [
                'project_id' => 99999,
                'vendor_name' => 'テスト業者',
                'category' => 'dev',
                'amount' => 100000,
                'invoice_date' => '2024-01-01',
            ]);

        $response->assertStatus(422);
    }

    /**
     * 認証なしでは外注費を作成できない
     */
    public function test_unauthenticated_user_cannot_create_external_cost(): void
    {
        $response = $this->postJson("{$this->apiPrefix}/external-costs/create", [
            'project_id' => $this->project->id,
            'vendor_name' => 'テスト業者',
            'category' => 'dev',
            'amount' => 100000,
            'invoice_date' => '2024-01-01',
        ]);

        $response->assertStatus(401);
    }

    /**
     * 各カテゴリで外注費を作成できる
     */
    public function test_can_create_external_cost_with_each_category(): void
    {
        $categories = ['dev', 'design', 'qa', 'translation', 'other'];

        foreach ($categories as $index => $category) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
                ->postJson("{$this->apiPrefix}/external-costs/create", [
                    'project_id' => $this->project->id,
                    'vendor_name' => "業者_{$category}",
                    'category' => $category,
                    'amount' => 100000 * ($index + 1),
                    'invoice_date' => '2024-01-01',
                ]);

            $response->assertStatus(200, "Failed to create external cost with category: {$category}");

            $this->assertDatabaseHas('external_costs', [
                'vendor_name' => "業者_{$category}",
                'category' => $category,
            ]);
        }
    }

    /**
     * 税抜金額で外注費を作成できる
     */
    public function test_can_create_external_cost_with_tax_excluded(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/external-costs/create", [
                'project_id' => $this->project->id,
                'vendor_name' => '税抜テスト業者',
                'category' => 'dev',
                'amount' => 100000,
                'tax_excluded' => true,
                'invoice_date' => '2024-01-01',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('external_costs', [
            'vendor_name' => '税抜テスト業者',
            'tax_excluded' => true,
        ]);

        // レスポンスで税込金額が計算されていることを確認
        $responseData = $response->json('data');
        $this->assertEqualsWithDelta(110000, $responseData['tax_included_amount'], 0.01);
    }
}
