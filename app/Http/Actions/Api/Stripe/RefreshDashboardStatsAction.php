<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\UseCase\RefreshStripeDashboardStatsUseCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class RefreshDashboardStatsAction extends BaseController
{
    public function __construct(
        private RefreshStripeDashboardStatsUseCase $refreshStripeDashboardStatsUseCase
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        try {
            $stats = $this->refreshStripeDashboardStatsUseCase->handle($id);

            return response()->json([
                'message' => __('stripe_account.dashboard_stats.refresh.success'),
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_account.dashboard_stats.refresh.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
