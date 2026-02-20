<?php

namespace App\Http\Actions\Api\Stripe;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ProcessSyncQueueAction extends BaseController
{
    /**
     * 同期キューを即時処理
     *
     * POST /api/v1/stripe/accounts/sync-queue/process
     */
    public function __invoke(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 50);

        try {
            // コマンドを実行
            $exitCode = Artisan::call('stripe:process-queue', [
                '--limit' => $limit,
            ]);

            $output = Artisan::output();

            if ($exitCode === 0) {
                return response()->json([
                    'message' => 'キュー処理を開始しました',
                    'data' => [
                        'output' => $output,
                    ],
                ]);
            } else {
                return response()->json([
                    'message' => 'キュー処理に失敗しました',
                    'data' => [
                        'output' => $output,
                    ],
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'キュー処理に失敗しました',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
