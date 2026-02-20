<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class GetSyncStatusAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 同期状態を取得
     *
     * GET /api/v1/stripe/accounts/{id}/sync-status
     */
    public function __invoke(int $id): JsonResponse
    {
        try {
            $syncStates = $this->syncService->getSyncStates($id);
            $unresolvedErrors = $this->syncService->getUnresolvedErrors($id);

            return response()->json([
                'message' => 'Sync status retrieved successfully',
                'data' => [
                    'sync_states' => $syncStates,
                    'unresolved_errors_count' => count($unresolvedErrors),
                    'supported_object_types' => $this->syncService->getSupportedObjectTypes(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve sync status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
