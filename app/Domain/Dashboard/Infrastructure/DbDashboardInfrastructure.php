<?php

namespace App\Domain\Dashboard\Infrastructure;

use App\Domain\Dashboard\Repository\DashboardRepository;
use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DbDashboardInfrastructure implements DashboardRepository
{
    public function getAdminDashboard(array $filters): array
    {
        $filterType = $filters['filter_type'] ?? 'month';

        [$dateFrom, $dateTo] = $this->getDateRange($filterType, $filters);

        $orderQuery = Order::whereBetween('created_at', [$dateFrom, $dateTo]);

        $totalOrders = (clone $orderQuery)->count();

        $revenue = (clone $orderQuery)
            ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::REFUNDED])
            ->sum('total_amount');

        $ordersByStatus = (clone $orderQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $allStatuses = OrderStatus::getValues();
        $ordersByStatusFull = [];
        foreach ($allStatuses as $status) {
            $ordersByStatusFull[$status] = $ordersByStatus[$status] ?? 0;
        }

        $revenueChart = $this->getRevenueChart($filterType, $filters, $dateFrom, $dateTo);

        $stockStats = Product::where('is_active', true)
            ->selectRaw('COALESCE(SUM(stock_quantity), 0) as total_quantity')
            ->selectRaw('COALESCE(SUM(stock_quantity * price_vnd), 0) as total_value')
            ->first();

        return [
            'total_orders' => $totalOrders,
            'revenue' => (float) $revenue,
            'total_stock_quantity' => (int) $stockStats->total_quantity,
            'total_stock_value' => (float) $stockStats->total_value,
            'revenue_chart' => $revenueChart,
            'orders_by_status' => $ordersByStatusFull,
            'filter_type' => $filterType,
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
        ];
    }

    private function getDateRange(string $filterType, array $filters): array
    {
        switch ($filterType) {
            case 'date_range':
                $from = Carbon::parse($filters['date_from'])->startOfDay();
                $to = Carbon::parse($filters['date_to'])->endOfDay();
                return [$from, $to];

            case 'year':
                $year = (int) $filters['year'];
                return [
                    Carbon::create($year, 1, 1)->startOfDay(),
                    Carbon::create($year, 12, 31)->endOfDay(),
                ];

            case 'month':
            default:
                $date = !empty($filters['month'])
                    ? Carbon::createFromFormat('Y-m', $filters['month'])
                    : now();
                return [
                    $date->copy()->startOfMonth()->startOfDay(),
                    $date->copy()->endOfMonth()->endOfDay(),
                ];
        }
    }

    private function getRevenueChart(string $filterType, array $filters, $dateFrom, $dateTo): array
    {
        switch ($filterType) {
            case 'year':
                return $this->getRevenueByMonth((int) $filters['year']);

            case 'date_range':
            case 'month':
            default:
                return $this->getRevenueByDay($dateFrom, $dateTo);
        }
    }

    private function getRevenueByDay($dateFrom, $dateTo): array
    {
        $data = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::REFUNDED])
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as revenue')
            ->selectRaw('COUNT(*) as orders')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get()
            ->keyBy('date');

        $result = [];
        $current = $dateFrom->copy()->startOfDay();
        $end = $dateTo->copy()->startOfDay();

        while ($current->lte($end)) {
            $dateStr = $current->toDateString();
            $result[] = [
                'label' => $dateStr,
                'revenue' => (float) ($data[$dateStr]->revenue ?? 0),
                'orders' => (int) ($data[$dateStr]->orders ?? 0),
            ];
            $current->addDay();
        }

        return $result;
    }

    private function getRevenueByMonth(int $year): array
    {
        $data = Order::whereYear('created_at', $year)
            ->whereNotIn('status', [OrderStatus::CANCELLED, OrderStatus::REFUNDED])
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month')
            ->selectRaw('COALESCE(SUM(total_amount), 0) as revenue')
            ->selectRaw('COUNT(*) as orders')
            ->groupByRaw('EXTRACT(MONTH FROM created_at)')
            ->orderByRaw('EXTRACT(MONTH FROM created_at)')
            ->get()
            ->keyBy('month');

        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $result[] = [
                'label' => sprintf('%d-%02d', $year, $m),
                'revenue' => (float) ($data[$m]->revenue ?? 0),
                'orders' => (int) ($data[$m]->orders ?? 0),
            ];
        }

        return $result;
    }
}
