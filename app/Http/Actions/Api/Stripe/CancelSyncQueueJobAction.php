<?php

namespace App\Http\Actions\Api\Stripe;

use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeSyncJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CancelSyncQueueJobAction extends BaseController
{
    /**
     * キュージョブをキャンセル（履歴に失敗として残す）
     *
     * POST /api/v1/stripe/accounts/sync-queue/{id}/cancel
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $job = StripeSyncJob::find($id);

        if (!$job) {
            return response()->json([
                'message' => 'ジョブが見つかりません',
            ], 404);
        }

        // pending または in_progress のジョブのみキャンセル可能
        if (!in_array($job->status, [StripeSyncJob::STATUS_PENDING, StripeSyncJob::STATUS_IN_PROGRESS])) {
            return response()->json([
                'message' => '待機中または実行中のジョブのみキャンセルできます',
                'current_status' => $job->status,
            ], 422);
        }

        $cancelledBy = auth()->user()?->name ?? 'api';
        $job->cancel($cancelledBy);

        return response()->json([
            'message' => 'ジョブをキャンセルしました',
            'data' => [
                'id' => $job->id,
                'status' => $job->status,
                'cancelled_by' => $job->cancelled_by,
                'cancelled_at' => $job->cancelled_at?->toIso8601String(),
            ],
        ]);
    }
}
