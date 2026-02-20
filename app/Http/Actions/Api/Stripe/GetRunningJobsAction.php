<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetRunningJobsAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 実行中の同期ジョブを取得
     *
     * GET /api/v1/stripe/accounts/{id}/sync-jobs/running
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            $jobs = $this->syncService->getRunningJobs($id);

            return response()->json([
                'message' => 'Running sync jobs retrieved successfully',
                'data' => [
                    'jobs' => $jobs,
                    'count' => count($jobs),
                    'has_running_jobs' => count($jobs) > 0,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve running sync jobs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
