<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResolveSyncErrorAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 同期エラーを解決済みにマーク
     *
     * POST /api/v1/stripe/accounts/{id}/sync-errors/resolve
     *
     * Body:
     * - error_ids: array (optional) - 解決するエラーIDの配列
     * - object_type: string (optional) - 特定のオブジェクトタイプのエラーを全て解決
     * - resolve_all: bool (optional) - 全てのエラーを解決
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $updater = auth()->user()?->name ?? 'api';
            $errorIds = $request->input('error_ids', []);
            $objectType = $request->input('object_type');
            $resolveAll = $request->boolean('resolve_all', false);

            $resolvedCount = 0;

            if ($resolveAll || $objectType) {
                // 全てまたは特定オブジェクトタイプのエラーを解決
                $resolvedCount = $this->syncService->resolveAllErrors($id, $objectType, $updater);
            } elseif (!empty($errorIds)) {
                // 指定されたエラーIDのみ解決
                $resolvedCount = $this->syncService->resolveErrors($errorIds, $updater);
            } else {
                return response()->json([
                    'message' => 'No errors specified to resolve. Provide error_ids, object_type, or set resolve_all to true.',
                ], 422);
            }

            return response()->json([
                'message' => 'Sync errors resolved successfully',
                'data' => [
                    'resolved_count' => $resolvedCount,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to resolve sync errors',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
