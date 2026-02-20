<?php

namespace App\Docs\Actions\Api\StripeAccount;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripeアカウント",
 *     description="Stripe Account"
 * )
 *
 * @OA\Put(
 *     path="/api/v1/stripe/accounts/{id}",
 *     summary="Update Stripe account",
 *     description="Update an existing Stripe account configuration. All fields except user_id are optional. Requires 'update-stripe-account' permission.",
 *     operationId="updateStripeAccount",
 *     tags={"Stripeアカウント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="1")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", maxLength=255, nullable=true, example="Updated Stripe Account"),
 *             @OA\Property(property="stripe_key", type="string", nullable=true, example="pk_test_..."),
 *             @OA\Property(property="stripe_secret", type="string", nullable=true, example="sk_test_..."),
 *             @OA\Property(property="stripe_payment_link", type="string", nullable=true, example="https://buy.stripe.com/..."),
 *             @OA\Property(property="stripe_webhook_secret", type="string", nullable=true, example="whsec_...")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class UpdateStripeAccountDetailAction {}
