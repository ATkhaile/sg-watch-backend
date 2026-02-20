<?php

namespace App\Http\Actions\Api\Stripe;

use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeCustomer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchStripeCustomersAction extends BaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $stripeAccountId = $request->get('stripe_account_id');
        $limit = min((int) $request->get('limit', 20), 100);

        if (strlen($search) < 2) {
            return response()->json([
                'success' => true,
                'data' => [
                    'customers' => [],
                ],
                'status_code' => 200,
            ], 200);
        }

        $query = StripeCustomer::query();

        // Stripe Account IDでフィルタ
        if ($stripeAccountId) {
            $query->where('stripe_account_id', $stripeAccountId);
        }

        // メールアドレス、名前、顧客IDで検索
        $query->where(function ($q) use ($search) {
            $q->where('email', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('stripe_id', 'like', "%{$search}%");
        });

        $customers = $query->orderBy('name')
            ->limit($limit)
            ->get(['id', 'stripe_account_id', 'stripe_id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'data' => [
                'customers' => $customers->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'stripe_account_id' => $customer->stripe_account_id,
                        'stripe_id' => $customer->stripe_id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                    ];
                }),
            ],
            'status_code' => 200,
        ], 200);
    }
}
