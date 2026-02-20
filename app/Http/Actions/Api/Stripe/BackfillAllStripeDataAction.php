<?php

namespace App\Http\Actions\Api\Stripe;

use App\Domain\Stripe\Service\StripeSyncService;
use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BackfillAllStripeDataAction extends BaseController
{
    public function __construct(
        private StripeSyncService $syncService
    ) {
    }

    /**
     * 全アカウントのバックフィル実行（全データ取得）
     *
     * POST /api/v1/stripe/accounts/backfill-all
     */
    public function __invoke(Request $request): JsonResponse
    {
        // 全アカウントのバックフィルは時間がかかるため、タイムアウトを無制限に設定
        set_time_limit(0);

        try {
            $creator = auth()->user()?->name ?? 'api';
            $accounts = StripeAccount::all();
            $allResults = [];
            $hasAnyError = false;

            foreach ($accounts as $account) {
                try {
                    $results = $this->syncService->backfill($account->id, null, $creator);

                    $accountHasErrors = collect($results)->contains(function ($result) {
                        return isset($result['success']) && !$result['success'];
                    });

                    if ($accountHasErrors) {
                        $hasAnyError = true;
                    }

                    $allResults[] = [
                        'id' => $account->id,
                        'stripe_account_id' => $account->uuid,
                        'stripe_account_name' => $account->display_name ?? '',
                        'success' => !$accountHasErrors,
                        'data' => $results,
                    ];
                } catch (\Exception $e) {
                    $hasAnyError = true;
                    $allResults[] = [
                        'id' => $account->id,
                        'stripe_account_id' => $account->uuid,
                        'stripe_account_name' => $account->display_name ?? '',
                        'success' => false,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            return response()->json([
                'message' => $hasAnyError
                    ? __('stripe_account.sync.backfill_all.completed_with_errors')
                    : __('stripe_account.sync.backfill_all.success'),
                'data' => $allResults,
            ], $hasAnyError ? 207 : 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('stripe_account.sync.backfill_all.failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
