<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeSyncJob;
use App\Models\Stripe\StripeSyncState;
use Illuminate\Http\JsonResponse;

class GetSyncProgressAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 同期進捗をリアルタイムで取得
     *
     * GET /api/v1/stripe/accounts/{id}/sync-progress
     *
     * レスポンス:
     * - running_jobs: 実行中のジョブ一覧（進捗情報含む）
     * - sync_states: 各オブジェクトの同期状態
     * - recent_errors_count: 未解決エラー数
     * - overall_status: 全体ステータス（idle|syncing|error）
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            // 実行中のジョブを取得
            $runningJobs = StripeSyncJob::where('stripe_account_id', $id)
                ->inProgress()
                ->with('errors')
                ->orderBy('started_at', 'desc')
                ->get()
                ->map(function ($job) {
                    $duration = $job->started_at
                        ? now()->diffInSeconds($job->started_at)
                        : 0;

                    return [
                        'id' => $job->id,
                        'object_name' => $job->object_name,
                        'job_type' => $job->job_type,
                        'status' => $job->status,
                        'processed_count' => $job->processed_count,
                        'error_count' => $job->error_count,
                        'started_at' => $job->started_at?->toIso8601String(),
                        'duration_seconds' => $duration,
                        'message' => $job->message,
                    ];
                });

            // 各オブジェクトの同期状態を取得
            $syncStates = StripeSyncState::where('stripe_account_id', $id)
                ->orderBy('object_name')
                ->get()
                ->map(function ($state) {
                    return [
                        'object_name' => $state->object_name,
                        'last_synced_at' => $state->last_synced_at?->toIso8601String(),
                        'last_synced_id' => $state->last_synced_id,
                        'total_count' => $state->total_count,
                        'has_cursor' => !empty($state->cursor),
                    ];
                });

            // 未解決エラー数を取得
            $unresolvedErrorsCount = count($this->syncService->getUnresolvedErrors($id));

            // 最近のジョブ履歴（直近5件）
            $recentJobs = StripeSyncJob::where('stripe_account_id', $id)
                ->whereNotIn('status', [StripeSyncJob::STATUS_IN_PROGRESS, StripeSyncJob::STATUS_PENDING])
                ->orderBy('finished_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($job) {
                    return [
                        'id' => $job->id,
                        'object_name' => $job->object_name,
                        'job_type' => $job->job_type,
                        'status' => $job->status,
                        'processed_count' => $job->processed_count,
                        'error_count' => $job->error_count,
                        'started_at' => $job->started_at?->toIso8601String(),
                        'finished_at' => $job->finished_at?->toIso8601String(),
                        'message' => $job->message,
                    ];
                });

            // 全体ステータスを判定
            $overallStatus = 'idle';
            if ($runningJobs->count() > 0) {
                $overallStatus = 'syncing';
            } elseif ($unresolvedErrorsCount > 0) {
                $overallStatus = 'error';
            }

            return response()->json([
                'message' => 'Sync progress retrieved successfully',
                'data' => [
                    'overall_status' => $overallStatus,
                    'running_jobs' => $runningJobs,
                    'running_jobs_count' => $runningJobs->count(),
                    'sync_states' => $syncStates,
                    'unresolved_errors_count' => $unresolvedErrorsCount,
                    'recent_jobs' => $recentJobs,
                    'supported_object_types' => $this->syncService->getAllSupportedObjectTypes(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve sync progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
