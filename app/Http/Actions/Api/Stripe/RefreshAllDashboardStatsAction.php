<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\RefreshStripeDashboardStatsUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class RefreshAllDashboardStatsAction extends BaseController
{
    public function __construct(
        private RefreshStripeDashboardStatsUseCase $refreshStripeDashboardStatsUseCase
    ) {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $results = $this->refreshStripeDashboardStatsUseCase->handleAll();

            return response()->json([
                'message' => __('stripe_account.dashboard_stats.refresh_all.success'),
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_account.dashboard_stats.refresh_all.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
