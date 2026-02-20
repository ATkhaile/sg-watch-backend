<?php

namespace App\Docs\Actions\Api\StripeAccount;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripeアカウント",
 *     description="Stripe Account"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/stripe/accounts/create",
 *     summary="Create Stripe account",
 *     description="Create a new Stripe account configuration with user ID, name, API keys, and webhook secret. Requires 'create-stripe-account' permission.",
 *     operationId="createStripeAccount",
 *     tags={"Stripeアカウント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "name", "stripe_key", "stripe_secret", "stripe_webhook_secret"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", maxLength=255, example="My Stripe Account"),
 *             @OA\Property(property="stripe_key", type="string", example="pk_test_..."),
 *             @OA\Property(property="stripe_secret", type="string", example="sk_test_..."),
 *             @OA\Property(property="stripe_payment_link", type="string", nullable=true, example="https://buy.stripe.com/..."),
 *             @OA\Property(property="stripe_webhook_secret", type="string", example="whsec_...")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class CreateStripeAccountAction {}
