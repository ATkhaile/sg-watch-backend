<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncMonitoringService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetDbStatsAction extends BaseController
{
    public function __construct(
        private StripeSyncMonitoringService $monitoringService
    ) {
    }

    /**
     * DB統計を取得
     *
     * GET /api/v1/stripe/db-stats
     */
    public function __invoke(): JsonResponse
    {
        try {
            $stats = $this->monitoringService->getDbStats();

            return response()->json([
                'message' => __('stripe_sync.db_stats.retrieved'),
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_sync.db_stats.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
