<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Employee;
use App\Models\WorkLog;
use App\Models\HelpRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProductionReportRepository
{
    /**
     * アクティブなプロジェクト数を取得
     */
    public function getActiveProjectsCount(): int
    {
        return Project::where('status', 'active')->count();
    }

    /**
     * 期間内の総工数を取得（時間単位）
     */
    public function getTotalHoursByPeriod(Carbon $startDate, Carbon $endDate): float
    {
        $totalMinutes = WorkLog::whereBetween('work_date', [$startDate, $endDate])
            ->sum('duration_minutes');

        return $totalMinutes / 60;
    }

    /**
     * 期間内の総売上を取得（完了プロジェクト）
     */
    public function getTotalRevenueByPeriod(Carbon $startDate, Carbon $endDate): float
    {
        return Project::where('status', 'completed')
            ->whereBetween('actual_completion_date', [$startDate, $endDate])
            ->sum('contract_amount');
    }

    /**
     * アクティブな従業員数を取得
     */
    public function getActiveEmployeesCount(): int
    {
        return Employee::where('active', true)->count();
    }

    /**
     * 重要なHelp要請数を取得
     */
    public function getCriticalHelpRequestsCount(): int
    {
        return HelpRequest::whereIn('severity', ['high', 'critical'])
            ->whereNotIn('status', ['resolved', 'wont_fix', 'canceled'])
            ->count();
    }

    /**
     * ブロッカーHelp要請数を取得
     */
    public function getBlockerHelpRequestsCount(): int
    {
        return HelpRequest::where('blocker', true)
            ->whereNotIn('status', ['resolved', 'wont_fix', 'canceled'])
            ->count();
    }

    /**
     * アクティブなプロジェクトの進捗状況を取得
     */
    public function getProjectProgress(int $limit = 10): Collection
    {
        return Project::where('status', 'active')
            ->with(['workLogs'])
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'progress_percentage' => round($project->getProgressPercentage(), 1),
                    'planned_hours' => $project->planned_hours,
                    'actual_hours' => round($project->getActualHours(), 1),
                    'is_delayed' => $project->isDelayed(),
                    'end_date' => $project->end_date ? $project->end_date->format('Y-m-d') : null,
                ];
            })
            ->sortByDesc('progress_percentage')
            ->values()
            ->take($limit);
    }

    /**
     * 月次工数推移を取得
     */
    public function getMonthlyHoursTrend(int $months = 6): array
    {
        $monthlyHours = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $totalMinutes = WorkLog::whereBetween('work_date', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth()
            ])->sum('duration_minutes');

            $monthlyHours[] = [
                'month' => $month->format('Y-m'),
                'hours' => round($totalMinutes / 60, 1),
            ];
        }

        return $monthlyHours;
    }

    /**
     * プロジェクト別収益性を取得
     */
    public function getProjectProfitability(int $limit = 10): Collection
    {
        return Project::whereIn('status', ['active', 'completed'])
            ->get()
            ->map(function ($project) {
                $grossProfit = $project->getGrossProfit();
                $grossMargin = $project->getGrossMargin();

                return [
                    'id' => $project->id,
                    'code' => $project->code,
                    'name' => $project->name,
                    'contract_amount' => $project->contract_amount,
                    'gross_profit' => round($grossProfit, 0),
                    'gross_margin' => round($grossMargin, 1),
                    'status' => $project->status,
                ];
            })
            ->sortByDesc('gross_margin')
            ->values()
            ->take($limit);
    }

    /**
     * 従業員別生産性を取得
     */
    public function getEmployeeProductivity(Carbon $startDate, Carbon $endDate, int $limit = 10): Collection
    {
        return Employee::where('active', true)
            ->with(['workLogs' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('work_date', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($employee) {
                $totalMinutes = $employee->workLogs->sum('duration_minutes');
                $totalHours = $totalMinutes / 60;
                $standardHours = $employee->standard_hours_per_month;
                $utilization = $standardHours > 0 ? ($totalHours / $standardHours) * 100 : 0;

                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'total_hours' => round($totalHours, 1),
                    'standard_hours' => $standardHours,
                    'utilization' => round($utilization, 1),
                    'projects_count' => $employee->workLogs->pluck('project_id')->unique()->count(),
                ];
            })
            ->sortByDesc('utilization')
            ->values()
            ->take($limit);
    }

    /**
     * カテゴリ別工数分布を取得
     */
    public function getCategoryDistribution(Carbon $startDate, Carbon $endDate): Collection
    {
        $distribution = WorkLog::whereBetween('work_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(duration_minutes) as total_minutes')
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'hours' => round($item->total_minutes / 60, 1),
                    'percentage' => 0,
                ];
            });

        $totalHours = $distribution->sum('hours');
        if ($totalHours > 0) {
            $distribution = $distribution->map(function ($item) use ($totalHours) {
                $item['percentage'] = round(($item['hours'] / $totalHours) * 100, 1);
                return $item;
            });
        }

        return $distribution->values();
    }

    /**
     * 従業員の期間内工数ログを取得
     */
    public function getEmployeeWorkLogs(int $employeeId, string $startDate, string $endDate): Collection
    {
        return WorkLog::where('employee_id', $employeeId)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->with('project')
            ->get();
    }

    /**
     * 全従業員を取得
     */
    public function getAllEmployees(): Collection
    {
        return Employee::all();
    }

    /**
     * 従業員をIDで取得
     */
    public function getEmployeeById(int $employeeId): ?Employee
    {
        return Employee::find($employeeId);
    }

    /**
     * 従業員の月次稼働時間を取得
     */
    public function getEmployeeMonthlyHours(int $employeeId, string $startDate, string $endDate): Collection
    {
        return WorkLog::where('employee_id', $employeeId)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(work_date, "%Y-%m") as month, SUM(duration_minutes) as total_minutes')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($log) {
                return [
                    'month' => $log->month,
                    'hours' => round($log->total_minutes / 60, 1),
                    'minutes' => $log->total_minutes,
                ];
            });
    }

    /**
     * 従業員の現在のアサインメントを取得
     */
    public function getEmployeeCurrentAssignments(int $employeeId): Collection
    {
        return WorkLog::where('employee_id', $employeeId)
            ->where('work_date', '>=', now()->subDays(30))
            ->with('project')
            ->select('project_id', 'primary_role')
            ->groupBy('project_id', 'primary_role')
            ->get()
            ->map(function ($log) {
                return [
                    'project_id' => $log->project->id,
                    'project_code' => $log->project->code,
                    'project_name' => $log->project->name,
                    'role_name' => $log->primary_role,
                    'allocation_percentage' => null,
                    'start_date' => null,
                    'end_date' => null,
                    'status' => 'active',
                ];
            });
    }

    /**
     * 従業員の月次工数ログを取得（PDF生成用）
     */
    public function getEmployeeMonthlyWorkLogs(int $employeeId, string $startDate, string $endDate): Collection
    {
        return WorkLog::where('employee_id', $employeeId)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->with(['project'])
            ->orderBy('work_date')
            ->orderBy('project_id')
            ->get();
    }
}
