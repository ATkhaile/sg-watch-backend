<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncPaymentMethodsAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 支払い方法を同期
     *
     * POST /api/v1/stripe/accounts/{id}/sync-payment-methods
     *
     * Body:
     * - customer_id: string (optional) - 特定の顧客IDの支払い方法のみ同期
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        try {
            $customerId = $request->input('customer_id');
            $creator = auth()->user()?->name ?? 'api';

            if ($customerId) {
                // 特定の顧客の支払い方法のみ同期
                $result = $this->syncService->syncPaymentMethodsForCustomer($id, $customerId, $creator);

                return response()->json([
                    'message' => $result['success']
                        ? 'Payment methods synced successfully for customer'
                        : 'Failed to sync payment methods for customer',
                    'data' => $result,
                ], $result['success'] ? 200 : 500);
            }

            // 全顧客の支払い方法を同期
            $results = $this->syncService->syncAllPaymentMethods($id, $creator);

            $successCount = collect($results)->where('success', true)->count();
            $failCount = collect($results)->where('success', false)->count();

            return response()->json([
                'message' => $failCount > 0
                    ? 'Payment methods sync completed with some errors'
                    : 'All payment methods synced successfully',
                'data' => [
                    'results' => $results,
                    'summary' => [
                        'total_customers' => count($results),
                        'success_count' => $successCount,
                        'fail_count' => $failCount,
                    ],
                ],
            ], $failCount > 0 ? 207 : 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to sync payment methods',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
