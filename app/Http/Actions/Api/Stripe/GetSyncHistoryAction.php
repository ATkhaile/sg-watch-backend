<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncMonitoringService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetSyncHistoryAction extends BaseController
{
    public function __construct(
        private StripeSyncMonitoringService $monitoringService
    ) {
    }

    /**
     * 同期履歴を取得
     *
     * GET /api/v1/stripe/sync-history
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1|max:100',
                'stripe_account_id' => 'nullable|integer|exists:stripe_accounts,id',
                'status' => 'nullable|string|in:completed,failed,cancelled',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $page = (int) $request->input('page', 1);
            $limit = (int) $request->input('limit', 10);
            $accountId = $request->input('stripe_account_id');

            $result = $this->monitoringService->getSyncHistory(
                $page,
                $limit,
                $accountId ? (int) $accountId : null,
                $request->input('status'),
                $request->input('start_date'),
                $request->input('end_date')
            );

            return response()->json([
                'message' => __('stripe_sync.history.retrieved'),
                'data' => $result,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => __('validation.failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_sync.history.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
