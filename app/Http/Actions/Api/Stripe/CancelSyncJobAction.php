<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class CancelSyncJobAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 同期ジョブをキャンセル
     *
     * POST /api/v1/stripe/accounts/{id}/sync-jobs/{jobId}/cancel
     */
    public function __invoke(int $id, int $jobId): JsonResponse
    {
        try {
            $updater = auth()->user()?->name ?? 'api';
            $success = $this->syncService->cancelJob($jobId, $updater);

            if ($success) {
                return response()->json([
                    'message' => 'Sync job cancelled successfully',
                    'data' => [
                        'job_id' => $jobId,
                        'status' => 'cancelled',
                    ],
                ]);
            }

            return response()->json([
                'message' => 'Failed to cancel sync job. Job may not be in a cancellable state.',
                'data' => [
                    'job_id' => $jobId,
                ],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to cancel sync job',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
