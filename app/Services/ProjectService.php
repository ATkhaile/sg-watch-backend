<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {}

    /**
     * プロジェクト一覧を取得
     */
    public function getAllProjects(
        ?string $status = null,
        ?int $projectManagerId = null,
        ?string $startDate = null,
        ?string $endDate = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->projectRepository->findAll(
            status: $status,
            projectManagerId: $projectManagerId,
            startDate: $startDate,
            endDate: $endDate,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            perPage: $perPage
        );
    }

    /**
     * プロジェクト詳細を取得
     */
    public function getProjectDetail(int $id): array
    {
        $project = $this->projectRepository->findById($id);

        if (!$project) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        return [
            'success' => true,
            'data' => $project,
        ];
    }

    /**
     * プロジェクトを作成
     */
    public function createProject(array $data): array
    {
        $project = $this->projectRepository->create($data);

        return [
            'success' => true,
            'message' => 'プロジェクトを作成しました。',
            'data' => $project->load(['projectManager']),
        ];
    }

    /**
     * プロジェクトを更新
     */
    public function updateProject(int $id, array $data): array
    {
        $project = $this->projectRepository->findById($id);

        if (!$project) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        // external_cost_budgetがnullの場合は0に変換
        if (array_key_exists('external_cost_budget', $data) && $data['external_cost_budget'] === null) {
            $data['external_cost_budget'] = 0;
        }

        $this->projectRepository->update($id, $data);
        $updatedProject = $this->projectRepository->findById($id);

        return [
            'success' => true,
            'message' => 'プロジェクトを更新しました。',
            'data' => $updatedProject,
        ];
    }

    /**
     * プロジェクトを削除
     */
    public function deleteProject(int $id): array
    {
        $project = $this->projectRepository->findById($id);

        if (!$project) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        $this->projectRepository->delete($id);

        return [
            'success' => true,
            'message' => 'プロジェクトを削除しました。',
        ];
    }

    /**
     * プロジェクトの財務情報を取得
     */
    public function getProjectFinancials(int $id, ?string $fromDate = null, ?string $toDate = null): array
    {
        $data = $this->projectRepository->getProjectFinancials($id, $fromDate, $toDate);

        if (!$data) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        $project = $data['project'];
        $workLogs = $data['work_logs'];
        $externalCosts = $data['external_costs'];

        // 労務費計算
        $laborCost = 0;
        $totalMinutes = 0;
        $employeeCosts = [];

        foreach ($workLogs as $workLog) {
            $minuteCost = $workLog->employee->getMinuteCost($workLog->work_date);
            $cost = $workLog->duration_minutes * $minuteCost;
            $laborCost += $cost;
            $totalMinutes += $workLog->duration_minutes;

            if (!isset($employeeCosts[$workLog->employee_id])) {
                $employeeCosts[$workLog->employee_id] = [
                    'employee_id' => $workLog->employee_id,
                    'employee_name' => $workLog->employee->name,
                    'total_minutes' => 0,
                    'total_cost' => 0,
                ];
            }
            $employeeCosts[$workLog->employee_id]['total_minutes'] += $workLog->duration_minutes;
            $employeeCosts[$workLog->employee_id]['total_cost'] += $cost;
        }

        // 外注費計算
        $totalExternalCost = $externalCosts->sum('amount');
        $paidExternalCost = $externalCosts->where('paid_at', '!=', null)->sum('amount');
        $unpaidExternalCost = $totalExternalCost - $paidExternalCost;

        // カテゴリ別外注費
        $externalCostsByCategory = $externalCosts->groupBy('category')->map(function ($costs, $category) {
            return [
                'category' => $category,
                'total_amount' => $costs->sum('amount'),
                'paid_amount' => $costs->where('paid_at', '!=', null)->sum('amount'),
                'count' => $costs->count(),
            ];
        })->values();

        // 粗利計算
        $totalCost = $laborCost + $totalExternalCost;
        $grossProfit = $project->contract_amount - $totalCost;
        $grossMargin = $project->contract_amount > 0 ? ($grossProfit / $project->contract_amount) * 100 : 0;

        return [
            'success' => true,
            'data' => [
                'project' => [
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'contract_amount' => $project->contract_amount,
                ],
                'summary' => [
                    'revenue' => $project->contract_amount,
                    'labor_cost' => round($laborCost, 2),
                    'external_cost' => round($totalExternalCost, 2),
                    'total_cost' => round($totalCost, 2),
                    'gross_profit' => round($grossProfit, 2),
                    'gross_margin' => round($grossMargin, 2),
                    'total_hours' => round($totalMinutes / 60, 2),
                ],
                'external_costs' => [
                    'total' => round($totalExternalCost, 2),
                    'paid' => round($paidExternalCost, 2),
                    'unpaid' => round($unpaidExternalCost, 2),
                    'by_category' => $externalCostsByCategory,
                ],
                'employee_costs' => array_values($employeeCosts),
                'period' => [
                    'from' => $fromDate,
                    'to' => $toDate,
                ],
            ],
        ];
    }

    /**
     * プロジェクトの工数サマリーを取得
     */
    public function getProjectWorkLogsSummary(int $id): array
    {
        $data = $this->projectRepository->getProjectWorkLogsSummary($id);

        if (!$data) {
            return [
                'success' => false,
                'message' => 'プロジェクトが見つかりません。',
            ];
        }

        $project = $data['project'];
        $workLogs = $data['work_logs'];

        // 従業員別集計
        $employeeSummary = $workLogs->groupBy('employee_id')->map(function ($logs, $employeeId) {
            $employee = $logs->first()->employee;
            $totalMinutes = $logs->sum('duration_minutes');

            return [
                'employee_id' => $employeeId,
                'employee_name' => $employee->name,
                'employee_code' => $employee->employee_code,
                'total_hours' => round($totalMinutes / 60, 2),
                'total_minutes' => $totalMinutes,
                'work_days' => $logs->pluck('work_date')->unique()->count(),
            ];
        })->values();

        // カテゴリ別集計
        $categorySummary = $workLogs->groupBy('category')->map(function ($logs, $category) {
            return [
                'category' => $category,
                'total_hours' => round($logs->sum('duration_minutes') / 60, 2),
                'work_logs_count' => $logs->count(),
            ];
        })->values();

        // 日次集計
        $dailySummary = $workLogs->groupBy('work_date')->map(function ($logs, $date) {
            return [
                'date' => $date,
                'total_hours' => round($logs->sum('duration_minutes') / 60, 2),
                'employees_count' => $logs->pluck('employee_id')->unique()->count(),
            ];
        })->sortBy('date')->values();

        return [
            'success' => true,
            'data' => [
                'project' => [
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'planned_hours' => $project->planned_hours,
                ],
                'summary' => [
                    'total_hours' => round($workLogs->sum('duration_minutes') / 60, 2),
                    'total_work_logs' => $workLogs->count(),
                    'employees_count' => $workLogs->pluck('employee_id')->unique()->count(),
                    'work_days_count' => $workLogs->pluck('work_date')->unique()->count(),
                    'progress_percentage' => round($project->getProgressPercentage(), 2),
                ],
                'by_employee' => $employeeSummary,
                'by_category' => $categorySummary,
                'by_date' => $dailySummary,
            ],
        ];
    }
}
