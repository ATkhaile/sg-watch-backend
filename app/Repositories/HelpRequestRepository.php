<?php

namespace App\Repositories;

use App\Models\HelpRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class HelpRequestRepository
{
    /**
     * ヘルプリクエスト一覧を取得（ページネーション付き）
     */
    public function findAll(
        ?string $status = null,
        ?string $severity = null,
        ?int $projectId = null,
        ?int $assigneeId = null,
        ?bool $blockerOnly = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = HelpRequest::with(['project', 'requester', 'assignee']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($severity) {
            $query->where('severity', $severity);
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($assigneeId) {
            $query->where('assignee_id', $assigneeId);
        }

        if ($blockerOnly) {
            $query->where('blocker', true);
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * ヘルプリクエストを作成
     */
    public function create(array $data): HelpRequest
    {
        return HelpRequest::create($data);
    }

    /**
     * IDでヘルプリクエストを取得
     */
    public function findById(int $id): ?HelpRequest
    {
        return HelpRequest::with(['project', 'requester', 'assignee', 'relatedWorkLog'])->find($id);
    }

    /**
     * ヘルプリクエストを更新
     */
    public function update(int $id, array $data): bool
    {
        $helpRequest = HelpRequest::find($id);
        if (!$helpRequest) {
            return false;
        }

        return $helpRequest->update($data);
    }

    /**
     * ヘルプリクエストを削除
     */
    public function delete(int $id): bool
    {
        $helpRequest = HelpRequest::find($id);
        if (!$helpRequest) {
            return false;
        }

        return $helpRequest->delete();
    }
}
