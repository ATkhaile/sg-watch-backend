<?php

namespace App\Services;

use App\Repositories\HelpRequestRepository;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class HelpRequestService
{
    public function __construct(
        private HelpRequestRepository $helpRequestRepository
    ) {}

    /**
     * ヘルプリクエスト一覧を取得
     */
    public function getAllHelpRequests(
        ?string $status = null,
        ?string $severity = null,
        ?int $projectId = null,
        ?int $assigneeId = null,
        ?bool $blockerOnly = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->helpRequestRepository->findAll(
            status: $status,
            severity: $severity,
            projectId: $projectId,
            assigneeId: $assigneeId,
            blockerOnly: $blockerOnly,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            perPage: $perPage
        );
    }

    /**
     * ヘルプリクエストを作成
     */
    public function createHelpRequest(array $data, int $requesterId): array
    {
        // プロジェクトの存在確認（任意）
        if (isset($data['project_id'])) {
            $project = Project::find($data['project_id']);
            if (!$project) {
                return [
                    'success' => false,
                    'message' => 'プロジェクトが見つかりません。',
                ];
            }
        }

        // アサイニーの存在確認（任意）
        if (isset($data['assignee_id'])) {
            $assignee = Employee::find($data['assignee_id']);
            if (!$assignee) {
                return [
                    'success' => false,
                    'message' => '担当者が見つかりません。',
                ];
            }
        }

        $data['requester_id'] = $requesterId;

        $helpRequest = $this->helpRequestRepository->create($data);

        return [
            'success' => true,
            'message' => 'ヘルプリクエストを作成しました。',
            'data' => $helpRequest->load(['project', 'requester', 'assignee']),
        ];
    }

    /**
     * ヘルプリクエストを更新
     */
    public function updateHelpRequest(int $id, array $data): array
    {
        $helpRequest = $this->helpRequestRepository->findById($id);

        if (!$helpRequest) {
            return [
                'success' => false,
                'message' => 'ヘルプリクエストが見つかりません。',
            ];
        }

        $this->helpRequestRepository->update($id, $data);
        $updatedHelpRequest = $this->helpRequestRepository->findById($id);

        return [
            'success' => true,
            'message' => 'ヘルプリクエストを更新しました。',
            'data' => $updatedHelpRequest,
        ];
    }
}
