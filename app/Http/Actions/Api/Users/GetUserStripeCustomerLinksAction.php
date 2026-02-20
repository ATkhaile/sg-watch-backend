<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\UserStripeCustomerLink;
use Illuminate\Http\JsonResponse;

class GetUserStripeCustomerLinksAction extends BaseController
{
    public function __invoke(int $userId): JsonResponse
    {
        $links = UserStripeCustomerLink::where('user_id', $userId)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Stripe customer links retrieved successfully',
            'status_code' => 200,
            'data' => [
                'links' => $links->map(function ($link) {
                    return [
                        'id' => $link->id,
                        'stripe_customer_id' => $link->stripe_customer_id,
                        'stripe_account_id' => $link->stripe_account_id,
                        'label' => $link->label,
                        'is_primary' => $link->is_primary,
                        'created_at' => $link->created_at?->toISOString(),
                        'updated_at' => $link->updated_at?->toISOString(),
                    ];
                }),
            ],
        ], 200);
    }
}
