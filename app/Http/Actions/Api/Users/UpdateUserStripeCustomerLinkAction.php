<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\UserStripeCustomerLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateUserStripeCustomerLinkAction extends BaseController
{
    public function __invoke(Request $request, int $userId, int $linkId): JsonResponse
    {
        $request->validate([
            'label' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

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

        DB::beginTransaction();
        try {
            // If setting as primary, unset other primary links
            if ($request->has('is_primary') && $request->boolean('is_primary')) {
                UserStripeCustomerLink::where('user_id', $userId)
                    ->where('id', '!=', $linkId)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            if ($request->has('label')) {
                $link->label = $request->label;
            }
            if ($request->has('is_primary')) {
                $link->is_primary = $request->boolean('is_primary');
            }
            $link->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stripe customer link updated successfully',
                'status_code' => 200,
                'data' => [
                    'link' => [
                        'id' => $link->id,
                        'stripe_customer_id' => $link->stripe_customer_id,
                        'stripe_account_id' => $link->stripe_account_id,
                        'label' => $link->label,
                        'is_primary' => $link->is_primary,
                        'updated_at' => $link->updated_at?->toISOString(),
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Stripe customer link',
                'status_code' => 500,
            ], 500);
        }
    }
}
