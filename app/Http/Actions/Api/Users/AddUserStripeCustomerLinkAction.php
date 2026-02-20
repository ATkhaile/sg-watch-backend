<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\UserStripeCustomerLink;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddUserStripeCustomerLinkAction extends BaseController
{
    public function __invoke(Request $request, int $userId): JsonResponse
    {
        $request->validate([
            'stripe_customer_id' => 'required|string|max:255',
            'stripe_account_id' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'status_code' => 404,
            ], 404);
        }

        // Check for duplicate
        $exists = UserStripeCustomerLink::where('user_id', $userId)
            ->where('stripe_customer_id', $request->stripe_customer_id)
            ->where(function ($query) use ($request) {
                if ($request->stripe_account_id) {
                    $query->where('stripe_account_id', $request->stripe_account_id);
                } else {
                    $query->whereNull('stripe_account_id');
                }
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This Stripe customer ID is already linked to this user',
                'status_code' => 409,
            ], 409);
        }

        DB::beginTransaction();
        try {
            // If setting as primary, unset other primary links
            if ($request->boolean('is_primary')) {
                UserStripeCustomerLink::where('user_id', $userId)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            $link = UserStripeCustomerLink::create([
                'user_id' => $userId,
                'stripe_customer_id' => $request->stripe_customer_id,
                'stripe_account_id' => $request->stripe_account_id,
                'label' => $request->label,
                'is_primary' => $request->boolean('is_primary'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stripe customer link added successfully',
                'status_code' => 201,
                'data' => [
                    'link' => [
                        'id' => $link->id,
                        'stripe_customer_id' => $link->stripe_customer_id,
                        'stripe_account_id' => $link->stripe_account_id,
                        'label' => $link->label,
                        'is_primary' => $link->is_primary,
                        'created_at' => $link->created_at?->toISOString(),
                    ],
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add Stripe customer link',
                'status_code' => 500,
            ], 500);
        }
    }
}
