<?php

namespace App\Services;

use App\Repositories\WorkLogRepository;
use App\Models\WorkLog;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class WorkLogService
{
    public function __construct(
        private WorkLogRepository $workLogRepository
    ) {}

    /**
     * 従業員の工数ログ一覧を取得
     */
    public function getEmployeeWorkLogs(
        int $employeeId,
        ?string $startDate = null,
        ?string $endDate = null,
        ?int $projectId = null,
        string $sortBy = 'work_date',
        string $sortDirection = 'desc',
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->workLogRepository->findByEmployeeId(
            employeeId: $employeeId,
            startDate: $startDate,
            endDate: $endDate,
            projectId: $projectId,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            perPage: $perPage
        );
    }

    /**
     * 工数ログを作成
     */
    public function createWorkLog(array $data, int $createdByEmployeeId): array
    {
        // プロジェクトの存在確認
        $project = Project::find($data['project_id']);
        if (!$project) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        // プロジェクトのステータス確認
        if (!in_array($project->status, ['active', 'planned'])) {
            return [
                'success' => false,
                'message' => 'このプロジェクトには工数を登録できません。',
            ];
        }

        // 従業員の存在確認
        $employee = Employee::find($data['employee_id']);
        if (!$employee) {
            return [
                'success' => false,
                'message' => '従業員が見つかりません。',
            ];
        }

        // created_byを追加
        $data['created_by'] = $createdByEmployeeId;

        $workLog = $this->workLogRepository->create($data);

        return [
            'success' => true,
            'message' => '工数を登録しました。',
            'data' => $workLog->load(['project', 'employee', 'approvedBy']),
        ];
    }

    /**
     * 工数ログを更新
     */
    public function updateWorkLog(int $id, array $data, int $employeeId): array
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            return [
                'success' => false,
                'message' => '工数ログが見つかりません。',
            ];
        }

        // 承認済みの場合は編集不可
        if ($this->workLogRepository->isApproved($id)) {
            return [
                'success' => false,
                'message' => '承認済みの工数は編集できません。',
            ];
        }

        // 自分の工数のみ編集可能
        if ($workLog->employee_id !== $employeeId) {
            return [
                'success' => false,
                'message' => '他の従業員の工数は編集できません。',
            ];
        }

        $this->workLogRepository->update($id, $data);
        $updatedWorkLog = $this->workLogRepository->findById($id);

        return [
            'success' => true,
            'message' => '工数を更新しました。',
            'data' => $updatedWorkLog,
        ];
    }

    /**
     * 工数ログを削除
     */
    public function deleteWorkLog(int $id, int $employeeId): array
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            return [
                'success' => false,
                'message' => '工数ログが見つかりません。',
            ];
        }

        // 承認済みの場合は削除不可
        if ($this->workLogRepository->isApproved($id)) {
            return [
                'success' => false,
                'message' => '承認済みの工数は削除できません。',
            ];
        }

        // 自分の工数のみ削除可能
        if ($workLog->employee_id !== $employeeId) {
            return [
                'success' => false,
                'message' => '他の従業員の工数は削除できません。',
            ];
        }

        $this->workLogRepository->delete($id);

        return [
            'success' => true,
            'message' => '工数を削除しました。',
        ];
    }

    /**
     * 工数ログのコストを計算
     */
    public function calculateCost(WorkLog $workLog): float
    {
        return $workLog->getCost();
    }
}
