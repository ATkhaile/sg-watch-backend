<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    /**
     * プロジェクト一覧を取得
     */
    public function findAll(
        ?string $status = null,
        ?int $projectManagerId = null,
        ?string $startDate = null,
        ?string $endDate = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Project::with([
            'projectManager',
            'members.employee',
            'workLogs.employee',
            'externalCosts'
        ]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($projectManagerId) {
            $query->where('project_manager_id', $projectManagerId);
        }

        if ($startDate && $endDate) {
            $query->inPeriod($startDate, $endDate);
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * プロジェクトをIDで取得
     */
    public function findById(int $id): ?Project
    {
        return Project::with(['projectManager', 'members.employee'])->find($id);
    }

    /**
     * プロジェクト詳細を取得（関連データあり）
     */
    public function findByIdWithDetails(int $id): ?Project
    {
        return Project::with([
            'projectManager',
            'members.employee',
            'workLogs.employee',
            'externalCosts'
        ])->find($id);
    }

    /**
     * プロジェクトを作成
     */
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * プロジェクトを更新
     */
    public function update(int $id, array $data): bool
    {
        $project = Project::find($id);
        if (!$project) {
            return false;
        }

        return $project->update($data);
    }

    /**
     * プロジェクトを削除
     */
    public function delete(int $id): bool
    {
        $project = Project::find($id);
        if (!$project) {
            return false;
        }

        return $project->delete();
    }

    /**
     * プロジェクトの財務情報を取得
     */
    public function getProjectFinancials(int $id, ?string $fromDate = null, ?string $toDate = null): ?array
    {
        $project = Project::with(['workLogs.employee', 'externalCosts'])->find($id);

        if (!$project) {
            return null;
        }

        // 工数集計
        $workLogsQuery = $project->workLogs();
        if ($fromDate) {
            $workLogsQuery->where('work_date', '>=', $fromDate);
        }
        if ($toDate) {
            $workLogsQuery->where('work_date', '<=', $toDate);
        }
        $workLogs = $workLogsQuery->get();

        // 外注費集計
        $externalCostsQuery = $project->externalCosts();
        if ($fromDate) {
            $externalCostsQuery->where('invoice_date', '>=', $fromDate);
        }
        if ($toDate) {
            $externalCostsQuery->where('invoice_date', '<=', $toDate);
        }
        $externalCosts = $externalCostsQuery->get();

        return [
            'project' => $project,
            'work_logs' => $workLogs,
            'external_costs' => $externalCosts,
        ];
    }

    /**
     * プロジェクトの工数サマリーを取得
     */
    public function getProjectWorkLogsSummary(int $id): ?array
    {
        $project = Project::with(['workLogs.employee'])->find($id);

        if (!$project) {
            return null;
        }

        return [
            'project' => $project,
            'work_logs' => $project->workLogs,
        ];
    }
}
