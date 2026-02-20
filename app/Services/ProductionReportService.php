<?php

namespace App\Services;

use App\Repositories\ProductionReportRepository;
use Carbon\Carbon;

class ProductionReportService
{
    public function __construct(
        private ProductionReportRepository $reportRepository
    ) {}

    /**
     * ダッシュボードデータを取得
     */
    public function getDashboardData(string $period = 'month'): array
    {
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $summary = [
            'active_projects' => $this->reportRepository->getActiveProjectsCount(),
            'total_hours_this_month' => round($this->reportRepository->getTotalHoursByPeriod(
                now()->startOfMonth(),
                now()->endOfMonth()
            ), 1),
            'total_revenue_this_month' => $this->reportRepository->getTotalRevenueByPeriod(
                now()->startOfMonth(),
                now()->endOfMonth()
            ),
            'active_employees' => $this->reportRepository->getActiveEmployeesCount(),
            'critical_help_requests' => $this->reportRepository->getCriticalHelpRequestsCount(),
            'blocker_help_requests' => $this->reportRepository->getBlockerHelpRequestsCount(),
        ];

        return [
            'summary' => $summary,
            'project_progress' => $this->reportRepository->getProjectProgress(10),
            'monthly_hours_trend' => $this->reportRepository->getMonthlyHoursTrend(6),
            'project_profitability' => $this->reportRepository->getProjectProfitability(10),
            'employee_productivity' => $this->reportRepository->getEmployeeProductivity(
                now()->startOfMonth(),
                now()->endOfMonth(),
                10
            ),
            'category_distribution' => $this->reportRepository->getCategoryDistribution(
                now()->startOfMonth(),
                now()->endOfMonth()
            ),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * 従業員別利益貢献度を取得
     */
    public function getEmployeeProfitability(string $startDate, string $endDate): array
    {
        $employees = $this->reportRepository->getAllEmployees();
        $profitabilityData = [];

        foreach ($employees as $employee) {
            $employeeProfitability = $this->calculateEmployeeProfitability($employee, $startDate, $endDate);
            $profitabilityData[] = $employeeProfitability;
        }

        // 利益順でソート
        usort($profitabilityData, function ($a, $b) {
            return $b['total_profit'] <=> $a['total_profit'];
        });

        return [
            'employees' => $profitabilityData,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_employees' => count($profitabilityData),
                'profitable_employees' => count(array_filter($profitabilityData, fn($emp) => $emp['total_profit'] > 0)),
                'total_profit' => array_sum(array_column($profitabilityData, 'total_profit')),
            ]
        ];
    }

    /**
     * 従業員別利益を計算
     */
    private function calculateEmployeeProfitability($employee, string $startDate, string $endDate): array
    {
        $workLogs = $this->reportRepository->getEmployeeWorkLogs($employee->id, $startDate, $endDate);

        $projectProfitability = [];
        $totalProfit = 0;
        $totalRevenue = 0;
        $totalCost = 0;

        // プロジェクトごとにグループ化
        $projectGroups = $workLogs->groupBy('project_id');

        foreach ($projectGroups as $projectId => $logs) {
            $project = $logs->first()->project;

            if (!$project || !$project->role_budget_percentages) {
                continue;
            }

            $projectData = $this->calculateProjectProfitabilityForEmployee($employee, $project, $logs);

            if ($projectData['total_hours'] > 0) {
                $projectProfitability[] = $projectData;
                $totalProfit += $projectData['profit'];
                $totalRevenue += $projectData['revenue_contribution'];
                $totalCost += $projectData['labor_cost'];
            }
        }

        return [
            'employee_id' => $employee->id,
            'employee_code' => $employee->employee_code,
            'employee_name' => $employee->name,
            'role' => $employee->role,
            'total_profit' => round($totalProfit, 2),
            'total_revenue_contribution' => round($totalRevenue, 2),
            'total_labor_cost' => round($totalCost, 2),
            'profit_margin' => $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 2) : 0,
            'projects' => $projectProfitability,
            'project_count' => count($projectProfitability),
            'profitable_projects' => count(array_filter($projectProfitability, fn($p) => $p['profit'] > 0)),
        ];
    }

    /**
     * プロジェクト別従業員の利益を計算
     */
    private function calculateProjectProfitabilityForEmployee($employee, $project, $workLogs): array
    {
        $budgetPercentages = $project->role_budget_percentages;
        $externalCostBudget = $project->external_cost_budget;

        $totalHours = 0;
        $totalLaborCost = 0;
        $roleContributions = [];
        $roleLaborCosts = [];

        // 各工数ログから役割別貢献度を計算
        foreach ($workLogs as $log) {
            $hours = $log->duration_minutes / 60;
            $totalHours += $hours;

            // 従業員の時給を計算
            $hourlyRate = $this->getEmployeeHourlyRate($employee, $log->work_date);
            $logLaborCost = $hours * $hourlyRate;
            $totalLaborCost += $logLaborCost;

            // 実際の役割配分がある場合はそれを使用、なければ主要役割に配分
            if ($log->role_percentages) {
                foreach ($log->role_percentages as $role => $percentage) {
                    if ($percentage > 0) {
                        $roleHours = $hours * ($percentage / 100);
                        $roleContributions[$role] = ($roleContributions[$role] ?? 0) + $roleHours;
                        $roleLaborCosts[$role] = ($roleLaborCosts[$role] ?? 0) + ($logLaborCost * ($percentage / 100));
                    }
                }
            } else {
                $primaryRole = $log->primary_role ?? 'PG';
                $roleContributions[$primaryRole] = ($roleContributions[$primaryRole] ?? 0) + $hours;
                $roleLaborCosts[$primaryRole] = ($roleLaborCosts[$primaryRole] ?? 0) + $logLaborCost;
            }
        }

        // 役割別売上貢献度と利益を計算
        $revenueContribution = 0;
        $roleProfits = [];

        foreach ($roleContributions as $role => $hours) {
            if (isset($budgetPercentages[$role]) && $budgetPercentages[$role] > 0) {
                // 予算配分率に基づく売上配分
                $roleRevenueBudget = $externalCostBudget * ($budgetPercentages[$role] / 100);

                // 役割別予定工数に対する実際の工数比率
                $projectPlannedHours = $project->planned_hours;
                $rolePlannedHours = $projectPlannedHours * ($budgetPercentages[$role] / 100);

                if ($rolePlannedHours > 0) {
                    // 役割別予定工数に対する比率で計算
                    $contributionRatio = min($hours / $rolePlannedHours, 1.0);
                    $roleRevenueContribution = $roleRevenueBudget * $contributionRatio;
                    $revenueContribution += $roleRevenueContribution;

                    // 役割別利益を計算
                    $roleLaborCost = $roleLaborCosts[$role] ?? 0;
                    $roleProfits[$role] = $roleRevenueContribution - $roleLaborCost;
                }
            }
        }

        $profit = array_sum($roleProfits);

        return [
            'project_id' => $project->id,
            'project_code' => $project->code,
            'project_name' => $project->name,
            'total_hours' => round($totalHours, 2),
            'labor_cost' => round($totalLaborCost, 2),
            'revenue_contribution' => round($revenueContribution, 2),
            'profit' => round($profit, 2),
            'profit_margin' => $revenueContribution > 0 ? round(($profit / $revenueContribution) * 100, 2) : 0,
            'role_contributions' => $roleContributions,
            'budget_percentages' => $budgetPercentages,
        ];
    }

    /**
     * 従業員の時給を取得
     */
    private function getEmployeeHourlyRate($employee, $workDate): float
    {
        if (isset($employee->hourly_rate) && $employee->hourly_rate > 0) {
            return $employee->hourly_rate;
        }

        // デフォルト月給30万円、標準労働時間160時間
        $monthlySalary = 300000;
        $standardMonthlyHours = 160;

        return $monthlySalary / $standardMonthlyHours;
    }

    /**
     * 従業員詳細データを取得
     */
    public function getEmployeeDetail(int $employeeId, string $startDate, string $endDate): array
    {
        $employee = $this->reportRepository->getEmployeeById($employeeId);

        if (!$employee) {
            return [
                'success' => false,
                'message' => '従業員が見つかりません。',
            ];
        }

        $monthlyHours = $this->reportRepository->getEmployeeMonthlyHours($employeeId, $startDate, $endDate);
        $projectProfitability = $this->getEmployeeProjectProfitability($employee, $startDate, $endDate);
        $currentAssignments = $this->reportRepository->getEmployeeCurrentAssignments($employeeId);

        return [
            'success' => true,
            'data' => [
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'role' => $employee->role,
                    'hourly_rate' => $employee->hourly_rate ?? 1250,
                ],
                'monthly_hours' => $monthlyHours,
                'project_profitability' => $projectProfitability,
                'current_assignments' => $currentAssignments,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
            ],
        ];
    }

    /**
     * 従業員のプロジェクト別利益率を取得
     */
    private function getEmployeeProjectProfitability($employee, string $startDate, string $endDate): array
    {
        $workLogs = $this->reportRepository->getEmployeeWorkLogs($employee->id, $startDate, $endDate);
        $projectGroups = $workLogs->groupBy('project_id');
        $projectProfitability = [];

        foreach ($projectGroups as $projectId => $logs) {
            $project = $logs->first()->project;

            if (!$project || !$project->role_budget_percentages) {
                continue;
            }

            $projectData = $this->calculateProjectProfitabilityForEmployee($employee, $project, $logs);

            if ($projectData['total_hours'] > 0) {
                $projectProfitability[] = $projectData;
            }
        }

        // 利益率でソート
        usort($projectProfitability, function ($a, $b) {
            return $b['profit_margin'] <=> $a['profit_margin'];
        });

        return $projectProfitability;
    }

    /**
     * 月報データを取得
     */
    public function getMonthlyReportData(int $employeeId, string $yearMonth): array
    {
        $employee = $this->reportRepository->getEmployeeById($employeeId);

        if (!$employee) {
            return [
                'success' => false,
                'message' => '従業員が見つかりません。',
            ];
        }

        $date = Carbon::createFromFormat('Y-m', $yearMonth);
        $startDate = $date->copy()->startOfMonth()->format('Y-m-d');
        $endDate = $date->copy()->endOfMonth()->format('Y-m-d');

        $workLogs = $this->reportRepository->getEmployeeMonthlyWorkLogs($employeeId, $startDate, $endDate);

        if ($workLogs->isEmpty()) {
            return [
                'success' => false,
                'message' => '指定された期間に工数ログが見つかりません。',
            ];
        }

        $projectGroups = $workLogs->groupBy('project_id');
        $totalHours = $workLogs->sum('duration_minutes') / 60;
        $totalDays = $workLogs->pluck('work_date')->unique()->count();
        $projectCount = $projectGroups->count();

        return [
            'success' => true,
            'data' => [
                'employee' => $employee,
                'year_month' => $date->format('Y年m月'),
                'start_date' => $date->copy()->startOfMonth()->format('Y-m-d'),
                'end_date' => $date->copy()->endOfMonth()->format('Y-m-d'),
                'work_logs' => $workLogs,
                'project_groups' => $projectGroups,
                'total_hours' => round($totalHours, 1),
                'total_days' => $totalDays,
                'project_count' => $projectCount,
                'generated_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * 期間の開始日を取得
     */
    private function getStartDate(string $period): Carbon
    {
        return match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };
    }
}
