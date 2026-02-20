<?php

namespace App\Http\Actions\Api\Stripe;

use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeSyncJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExecuteSyncQueueJobAction extends BaseController
{
    /**
     * キュージョブを即時実行
     *
     * POST /api/v1/stripe/accounts/sync-queue/{id}/execute
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $job = StripeSyncJob::find($id);

        if (!$job) {
            return response()->json([
                'message' => 'ジョブが見つかりません',
            ], 404);
        }

        if ($job->status !== StripeSyncJob::STATUS_PENDING) {
            return response()->json([
                'message' => '待機中のジョブのみ実行できます',
                'current_status' => $job->status,
            ], 422);
        }

        $updater = auth()->user()?->name ?? 'api';
        $job->executeImmediately($updater);

        return response()->json([
            'message' => 'ジョブを即時実行対象にしました',
            'data' => [
                'id' => $job->id,
                'status' => $job->status,
                'scheduled_at' => null,
            ],
        ]);
    }
}
