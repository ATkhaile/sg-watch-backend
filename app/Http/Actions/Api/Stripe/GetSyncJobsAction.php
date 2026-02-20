<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetSyncJobsAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 同期ジョブ一覧を取得
     *
     * GET /api/v1/stripe/accounts/{id}/sync-jobs
     *
     * Query Parameters:
     * - status: string (pending|in_progress|completed|failed|cancelled)
     * - object_type: string
     * - job_type: string (backfill|incremental|webhook|manual)
     * - limit: int (default 50)
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $filters = [
                'status' => $request->input('status'),
                'object_type' => $request->input('object_type'),
                'job_type' => $request->input('job_type'),
                'limit' => $request->input('limit', 50),
            ];

            $jobs = $this->syncService->getJobs($id, array_filter($filters));

            return response()->json([
                'message' => 'Sync jobs retrieved successfully',
                'data' => [
                    'jobs' => $jobs,
                    'total' => count($jobs),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve sync jobs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
