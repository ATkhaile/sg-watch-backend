<?php

namespace App\Repositories;

use App\Models\WorkLog;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkLogRepository
{
    /**
     * 従業員IDで工数ログを取得（ページネーション付き）
     */
    public function findByEmployeeId(
        int $employeeId,
        ?string $startDate = null,
        ?string $endDate = null,
        ?int $projectId = null,
        string $sortBy = 'work_date',
        string $sortDirection = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = WorkLog::with(['project', 'employee', 'approvedBy'])
            ->where('employee_id', $employeeId);

        if ($startDate) {
            $query->where('work_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('work_date', '<=', $endDate);
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * 工数ログを作成
     */
    public function create(array $data): WorkLog
    {
        return WorkLog::create($data);
    }

    /**
     * 工数ログを更新
     */
    public function update(int $id, array $data): bool
    {
        $workLog = WorkLog::find($id);
        if (!$workLog) {
            return false;
        }

        return $workLog->update($data);
    }

    /**
     * 工数ログを削除
     */
    public function delete(int $id): bool
    {
        $workLog = WorkLog::find($id);
        if (!$workLog) {
            return false;
        }

        return $workLog->delete();
    }

    /**
     * IDで工数ログを取得
     */
    public function findById(int $id): ?WorkLog
    {
        return WorkLog::with(['project', 'employee', 'approvedBy'])->find($id);
    }

    /**
     * 承認済みかチェック
     */
    public function isApproved(int $id): bool
    {
        $workLog = WorkLog::find($id);
        return $workLog && $workLog->approved_at !== null;
    }

    /**
     * プロジェクトIDで工数ログを取得
     */
    public function findByProjectId(int $projectId): Collection
    {
        return WorkLog::with(['employee'])
            ->where('project_id', $projectId)
            ->get();
    }

    /**
     * 従業員が特定のプロジェクトにアクセス可能かチェック
     */
    public function canEmployeeAccessProject(int $employeeId, int $projectId): bool
    {
        return WorkLog::where('employee_id', $employeeId)
            ->where('project_id', $projectId)
            ->exists();
    }
}
