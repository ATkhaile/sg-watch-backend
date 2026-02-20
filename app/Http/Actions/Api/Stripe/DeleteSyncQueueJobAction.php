<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncMonitoringService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class DeleteSyncQueueJobAction extends BaseController
{
    public function __construct(
        private StripeSyncMonitoringService $monitoringService
    ) {
    }

    /**
     * ペンディングジョブを削除
     *
     * DELETE /api/v1/stripe/sync-queue/{id}
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            $deleted = $this->monitoringService->deleteQueueJob($id);

            if (!$deleted) {
                return response()->json([
                    'message' => __('stripe_sync.queue.delete_failed'),
                    'error' => 'Job not found or not in pending status',
                ], 404);
            }

            return response()->json([
                'message' => __('stripe_sync.queue.deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_sync.queue.delete_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
