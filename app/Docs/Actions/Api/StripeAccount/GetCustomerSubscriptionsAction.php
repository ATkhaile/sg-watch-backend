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
 *     path="/api/v1/stripe/accounts/customers/subscriptions",
 *     summary="Get customer subscriptions",
 *     description="Retrieve paginated list of customer subscriptions. Public endpoint (no authentication required).",
 *     operationId="getCustomerSubscriptions",
 *     tags={"Stripeアカウント"},
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, maximum=100, example=10)
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetCustomerSubscriptionsAction {}
