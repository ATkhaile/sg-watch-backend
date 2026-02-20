<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetSyncErrorsAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 同期エラーを取得
     *
     * GET /api/v1/stripe/accounts/{id}/sync-errors
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $objectType = $request->input('object_type');
            $errors = $this->syncService->getUnresolvedErrors($id, $objectType);

            return response()->json([
                'message' => 'Sync errors retrieved successfully',
                'data' => [
                    'errors' => $errors,
                    'total_count' => count($errors),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve sync errors',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
