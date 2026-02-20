<?php

namespace App\Docs\Actions\Api\StripeAccount;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripeアカウント",
 *     description="Stripe Account"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/stripe/accounts/{stripe_account_id}/payment-links",
 *     summary="Get Stripe payment links",
 *     description="Retrieve payment links from Stripe API for a specific account with pagination and filtering. Requires authentication.",
 *     operationId="getStripePaymentLinks",
 *     tags={"Stripeアカウント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="stripe_account_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, maximum=100, example=10)
 *     ),
 *     @OA\Parameter(
 *         name="starting_after",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", description="Cursor for pagination")
 *     ),
 *     @OA\Parameter(
 *         name="ending_before",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", description="Cursor for pagination")
 *     ),
 *     @OA\Parameter(
 *         name="active",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="boolean", description="Filter by active status")
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetStripePaymentLinksAction {}
