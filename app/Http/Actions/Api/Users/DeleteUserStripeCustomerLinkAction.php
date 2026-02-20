<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\UserStripeCustomerLink;
use Illuminate\Http\JsonResponse;

class DeleteUserStripeCustomerLinkAction extends BaseController
{
    public function __invoke(int $userId, int $linkId): JsonResponse
    {
        $link = UserStripeCustomerLink::where('user_id', $userId)
            ->where('id', $linkId)
            ->first();

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe customer link not found',
                'status_code' => 404,
            ], 404);
        }

        $link->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stripe customer link deleted successfully',
            'status_code' => 200,
        ], 200);
    }
}
