<?php

namespace Tests\Feature\Project;

use App\Enums\Role;
use App\Models\User;
use App\Models\Project;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    private string $apiPrefix = '/api/v1';
    private ?string $adminToken = null;
    private User $adminUser;

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

        // 管理者でログイン
        $loginResponse = $this->postJson("{$this->apiPrefix}/login", [
            'email' => 'admin@example.com',
            'password' => 'AdminPass123',
        ]);

        $this->adminToken = $loginResponse->json('token');
    }

    /**
     * プロジェクトを作成できる
     */
    public function test_can_create_project(): void
    {
        $projectData = [
            'code' => 'PRJ-001',
            'name' => 'テストプロジェクト',
            'client_name' => 'テストクライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 1000,
            'contract_amount' => 10000000,
            'external_cost_budget' => 2000000,
            'status' => 'active',
            'progress_method' => 'hours_based',
            'description' => 'テストプロジェクトの説明',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/projects/create", $projectData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'code' => 'PRJ-001',
            'name' => 'テストプロジェクト',
            'client_name' => 'テストクライアント',
            'status' => 'active',
        ]);
    }

    /**
     * プロジェクトを更新できる
     */
    public function test_can_update_project(): void
    {
        // プロジェクトを作成
        $project = Project::create([
            'code' => 'PRJ-002',
            'name' => '元のプロジェクト名',
            'client_name' => '元のクライアント名',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 500,
            'contract_amount' => 5000000,
            'status' => 'planned',
            'progress_method' => 'hours_based',
        ]);

        $updateData = [
            'code' => 'PRJ-002',
            'name' => '更新後のプロジェクト名',
            'client_name' => '更新後のクライアント名',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 800,
            'contract_amount' => 8000000,
            'status' => 'active',
            'progress_method' => 'hours_based',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("{$this->apiPrefix}/projects/{$project->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => '更新後のプロジェクト名',
            'client_name' => '更新後のクライアント名',
            'status' => 'active',
            'planned_hours' => 800,
        ]);
    }

    /**
     * プロジェクトを削除できる（ソフトデリート）
     */
    public function test_can_delete_project(): void
    {
        // プロジェクトを作成
        $project = Project::create([
            'code' => 'PRJ-003',
            'name' => '削除対象プロジェクト',
            'client_name' => 'クライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 100,
            'contract_amount' => 1000000,
            'status' => 'planned',
            'progress_method' => 'hours_based',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("{$this->apiPrefix}/projects/{$project->id}");

        $response->assertStatus(200);

        // ソフトデリートされていることを確認
        $this->assertSoftDeleted('projects', [
            'id' => $project->id,
        ]);
    }

    /**
     * プロジェクト詳細を取得できる
     */
    public function test_can_get_project_detail(): void
    {
        // プロジェクトを作成
        $project = Project::create([
            'code' => 'PRJ-004',
            'name' => '詳細取得テスト',
            'client_name' => 'テストクライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 200,
            'contract_amount' => 2000000,
            'status' => 'active',
            'progress_method' => 'hours_based',
            'description' => '詳細取得テストの説明',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("{$this->apiPrefix}/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 'PRJ-004',
                'name' => '詳細取得テスト',
            ]);
    }

    /**
     * 存在しないプロジェクトを取得しようとすると404
     */
    public function test_get_nonexistent_project_returns_404(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("{$this->apiPrefix}/projects/99999");

        $response->assertStatus(404);
    }

    /**
     * 存在しないプロジェクトを削除しようとすると404
     */
    public function test_delete_nonexistent_project_returns_404(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("{$this->apiPrefix}/projects/99999");

        $response->assertStatus(404);
    }

    /**
     * バリデーションエラー: 必須項目なしで作成できない
     */
    public function test_cannot_create_project_without_required_fields(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/projects/create", [
                'name' => 'テスト',
            ]);

        $response->assertStatus(422);
    }

    /**
     * バリデーションエラー: 重複コードで作成できない
     */
    public function test_cannot_create_project_with_duplicate_code(): void
    {
        // 既存プロジェクトを作成
        Project::create([
            'code' => 'PRJ-DUP',
            'name' => '既存プロジェクト',
            'client_name' => 'クライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 100,
            'contract_amount' => 1000000,
            'status' => 'planned',
            'progress_method' => 'hours_based',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/projects/create", [
                'code' => 'PRJ-DUP',
                'name' => '新規プロジェクト',
                'client_name' => 'クライアント',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'planned_hours' => 100,
                'contract_amount' => 1000000,
                'status' => 'planned',
                'progress_method' => 'hours_based',
            ]);

        $response->assertStatus(422);
    }

    /**
     * 認証なしではプロジェクトを作成できない
     */
    public function test_unauthenticated_user_cannot_create_project(): void
    {
        $response = $this->postJson("{$this->apiPrefix}/projects/create", [
            'code' => 'PRJ-AUTH',
            'name' => 'テスト',
            'client_name' => 'クライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 100,
            'contract_amount' => 1000000,
            'status' => 'planned',
            'progress_method' => 'hours_based',
        ]);

        $response->assertStatus(401);
    }

    /**
     * 各ステータスでプロジェクトを作成できる
     */
    public function test_can_create_project_with_each_status(): void
    {
        $statuses = ['planned', 'active', 'on_hold', 'completed', 'canceled'];

        foreach ($statuses as $index => $status) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
                ->postJson("{$this->apiPrefix}/projects/create", [
                    'code' => "PRJ-STATUS-{$index}",
                    'name' => "ステータステスト {$status}",
                    'client_name' => 'クライアント',
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-12-31',
                    'planned_hours' => 100,
                    'contract_amount' => 1000000,
                    'status' => $status,
                    'progress_method' => 'hours_based',
                ]);

            $response->assertStatus(200, "Failed to create project with status: {$status}");

            $this->assertDatabaseHas('projects', [
                'code' => "PRJ-STATUS-{$index}",
                'status' => $status,
            ]);
        }
    }

    /**
     * 役割別予算配分でプロジェクトを作成できる
     */
    public function test_can_create_project_with_role_budget_percentages(): void
    {
        $projectData = [
            'code' => 'PRJ-ROLE',
            'name' => '役割予算テスト',
            'client_name' => 'クライアント',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'planned_hours' => 1000,
            'contract_amount' => 10000000,
            'role_budget_percentages' => [
                'PM' => 20,
                'SE' => 30,
                'PG' => 40,
                'QA' => 10,
            ],
            'status' => 'active',
            'progress_method' => 'hours_based',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("{$this->apiPrefix}/projects/create", $projectData);

        $response->assertStatus(200);

        $project = Project::where('code', 'PRJ-ROLE')->first();
        $this->assertNotNull($project);
        $this->assertEquals(20, $project->role_budget_percentages['PM']);
        $this->assertEquals(30, $project->role_budget_percentages['SE']);
    }
}
