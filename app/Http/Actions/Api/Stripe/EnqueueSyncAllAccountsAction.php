<?php

namespace App\Http\Actions\Api\Stripe;

use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeSyncJob;
use App\Models\Stripe\StripeAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnqueueSyncAllAccountsAction extends BaseController
{
    /**
     * 同期対象オブジェクト
     */
    private const SYNC_OBJECTS = [
        'customers',
        'products',
        'prices',
        'payment_intents',
        'charges',
        'subscriptions',
        'subscription_items',
        'invoices',
        'invoice_items',
        'refunds',
        'payment_methods',
        'payment_links',
        'checkout_sessions',
        'balance_transactions',
        'payouts',
        'disputes',
        'credit_notes',
        'events',
    ];

    /**
     * 全アカウントの同期ジョブをキューに登録
     *
     * POST /api/v1/stripe/accounts/sync-queue/enqueue-all
     */
    public function __invoke(Request $request): JsonResponse
    {
        $creator = auth()->user()?->name ?? 'api';
        $jobType = $request->input('job_type', 'manual');
        $scheduledAt = $request->input('scheduled_at');

        // アクティブなStripeアカウントを取得
        $accounts = StripeAccount::where('status', 'active')->get();

        if ($accounts->isEmpty()) {
            return response()->json([
                'message' => 'アクティブなStripeアカウントが見つかりません',
            ], 404);
        }

        $jobsCreated = 0;

        DB::beginTransaction();
        try {
            foreach ($accounts as $account) {
                foreach (self::SYNC_OBJECTS as $objectName) {
                    // 同じアカウント・オブジェクトでpendingまたはin_progressのジョブがあればスキップ
                    $existingJob = StripeSyncJob::where('stripe_account_id', $account->id)
                        ->where('object_name', $objectName)
                        ->whereIn('status', [StripeSyncJob::STATUS_PENDING, StripeSyncJob::STATUS_IN_PROGRESS])
                        ->exists();

                    if ($existingJob) {
                        continue;
                    }

                    StripeSyncJob::create([
                        'stripe_account_id' => $account->id,
                        'object_name' => $objectName,
                        'status' => StripeSyncJob::STATUS_PENDING,
                        'job_type' => $jobType,
                        'scheduled_at' => $scheduledAt ? \Carbon\Carbon::parse($scheduledAt) : null,
                        'creator' => $creator,
                    ]);

                    $jobsCreated++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'ジョブの登録に失敗しました',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => '同期ジョブをキューに登録しました',
            'data' => [
                'accounts_count' => $accounts->count(),
                'jobs_created' => $jobsCreated,
            ],
        ]);
    }
}
