<?php

namespace App\Http\Actions\Api\Stripe;

use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeSyncJob;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RescheduleSyncQueueJobAction extends BaseController
{
    /**
     * キュージョブの予定日時を変更
     *
     * PUT /api/v1/stripe/accounts/sync-queue/{id}/reschedule
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ], [
            'scheduled_at.required' => '予定日時を指定してください',
            'scheduled_at.date' => '有効な日時を指定してください',
            'scheduled_at.after' => '未来の日時を指定してください',
        ]);

        $job = StripeSyncJob::find($id);

        if (!$job) {
            return response()->json([
                'message' => 'ジョブが見つかりません',
            ], 404);
        }

        if ($job->status !== StripeSyncJob::STATUS_PENDING) {
            return response()->json([
                'message' => '待機中のジョブのみスケジュール変更できます',
                'current_status' => $job->status,
            ], 422);
        }

        $updater = auth()->user()?->name ?? 'api';
        $scheduledAt = Carbon::parse($request->input('scheduled_at'));
        $job->reschedule($scheduledAt, $updater);

        return response()->json([
            'message' => '予定日時を変更しました',
            'data' => [
                'id' => $job->id,
                'status' => $job->status,
                'scheduled_at' => $job->fresh()->scheduled_at?->toIso8601String(),
            ],
        ]);
    }
}
