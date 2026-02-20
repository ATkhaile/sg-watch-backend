<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncMonitoringService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetSyncStatsAction extends BaseController
{
    public function __construct(
        private StripeSyncMonitoringService $monitoringService
    ) {
    }

    /**
     * チャート用同期統計を取得
     *
     * GET /api/v1/stripe/sync-stats
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'view' => 'required|string|in:hour,day,week,month,3months,year',
                'stripe_account_id' => 'nullable|integer|exists:stripe_accounts,id',
            ]);

            $view = $request->input('view', 'day');
            $accountId = $request->input('stripe_account_id');

            $stats = $this->monitoringService->getSyncStats(
                $view,
                $accountId ? (int) $accountId : null
            );

            return response()->json([
                'message' => __('stripe_sync.stats.retrieved'),
                'data' => $stats,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('validation.failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_sync.stats.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
