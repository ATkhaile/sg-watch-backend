<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class SyncSubscriptionItemsAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * サブスクリプションアイテムを同期
     *
     * POST /api/v1/stripe/accounts/{id}/sync-subscription-items
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            $creator = auth()->user()?->name ?? 'api';
            $results = $this->syncService->syncAllSubscriptionItems($id, $creator);

            $successCount = collect($results)->where('success', true)->count();
            $failCount = collect($results)->where('success', false)->count();

            return response()->json([
                'message' => $failCount > 0
                    ? 'Subscription items sync completed with some errors'
                    : 'All subscription items synced successfully',
                'data' => [
                    'results' => $results,
                    'summary' => [
                        'total_subscriptions' => count($results),
                        'success_count' => $successCount,
                        'fail_count' => $failCount,
                    ],
                ],
            ], $failCount > 0 ? 207 : 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to sync subscription items',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
