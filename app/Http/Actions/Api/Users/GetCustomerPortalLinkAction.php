<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Controllers\BaseController;
use App\Models\Stripe\StripeAccount;
use App\Models\UserStripeCustomerLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class GetCustomerPortalLinkAction extends BaseController
{
    public function __invoke(Request $request, int $userId, int $linkId): JsonResponse
    {
        try {
            // ユーザーのStripe顧客リンクを取得
            $customerLink = UserStripeCustomerLink::where('id', $linkId)
                ->where('user_id', $userId)
                ->first();

            if (!$customerLink) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe顧客リンクが見つかりません',
                    'status_code' => 404,
                ], 404);
            }

            // Stripeアカウントを取得
            $stripeAccount = StripeAccount::where('stripe_account_id', $customerLink->stripe_account_id)
                ->orWhere('uuid', $customerLink->stripe_account_id)
                ->first();

            if (!$stripeAccount || !$stripeAccount->stripe_secret_key) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripeアカウントが設定されていません',
                    'status_code' => 400,
                ], 400);
            }

            // Stripeクライアントを作成
            $stripe = new StripeClient($stripeAccount->stripe_secret_key);

            // カスタマーポータルセッションを作成
            $returnUrl = env('BASE_FRONTEND_URL', 'http://localhost:3000/') . 'admin/users?id=' . $userId;

            $portalSession = $stripe->billingPortal->sessions->create([
                'customer' => $customerLink->stripe_customer_id,
                'return_url' => $returnUrl,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'カスタマーポータルリンクを取得しました',
                'status_code' => 200,
                'data' => [
                    'portal_url' => $portalSession->url,
                ],
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            \Log::error('Stripe Invalid Request Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe顧客が見つかりません: ' . $e->getMessage(),
                'status_code' => 400,
            ], 400);
        } catch (\Exception $e) {
            \Log::error('GetCustomerPortalLinkAction error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'カスタマーポータルリンクの取得に失敗しました: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
