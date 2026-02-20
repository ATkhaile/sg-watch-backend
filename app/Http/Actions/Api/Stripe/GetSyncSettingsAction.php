<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncMonitoringService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetSyncSettingsAction extends BaseController
{
    public function __construct(
        private StripeSyncMonitoringService $monitoringService
    ) {
    }

    /**
     * 同期設定を取得
     *
     * GET /api/v1/stripe/sync-settings
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $accountId = $request->input('stripe_account_id');
            $settings = $this->monitoringService->getSettings($accountId ? (int) $accountId : null);

            return response()->json([
                'message' => __('stripe_sync.settings.retrieved'),
                'data' => $settings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_sync.settings.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
