<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncMonitoringService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateSyncSettingsAction extends BaseController
{
    public function __construct(
        private StripeSyncMonitoringService $monitoringService
    ) {
    }

    /**
     * 同期設定を更新
     *
     * PUT /api/v1/stripe/sync-settings
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'stripe_account_id' => 'nullable|integer|exists:stripe_accounts,id',
                'auto_sync_enabled' => 'nullable|boolean',
                'webhook_enabled' => 'nullable|boolean',
                'sync_frequency' => 'nullable|string|in:30min,1_hour,6_hours,12_hours,1_day,2_days,3_days,1_week',
            ]);

            $updater = auth()->user()?->name ?? 'api';
            $settings = $this->monitoringService->updateSettings($request->all(), $updater);

            return response()->json([
                'message' => __('stripe_sync.settings.updated'),
                'data' => $settings,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('validation.failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_sync.settings.update_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
