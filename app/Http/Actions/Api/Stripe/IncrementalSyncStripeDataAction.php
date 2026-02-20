<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncrementalSyncStripeDataAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 差分同期実行
     *
     * POST /api/v1/stripe/accounts/{id}/sync
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $objectType = $request->input('object_type');
            $creator = auth()->user()?->name ?? 'api';

            // オブジェクトタイプが指定された場合、サポートされているか確認
            if ($objectType && !in_array($objectType, $this->syncService->getSupportedObjectTypes())) {
                return response()->json([
                    'message' => __('stripe_account.sync.errors.unsupported_object_type'),
                    'supported_types' => $this->syncService->getSupportedObjectTypes(),
                ], 422);
            }

            $results = $this->syncService->incrementalSync($id, $objectType, $creator);

            $hasErrors = collect($results)->contains(function ($result) {
                return isset($result['success']) && !$result['success'];
            });

            return response()->json([
                'message' => $hasErrors
                    ? __('stripe_account.sync.incremental.completed_with_errors')
                    : __('stripe_account.sync.incremental.success'),
                'data' => $results,
            ], $hasErrors ? 207 : 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_account.sync.incremental.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
