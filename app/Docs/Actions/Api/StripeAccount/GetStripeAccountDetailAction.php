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
 *     path="/api/v1/stripe/accounts/{id}",
 *     summary="Get Stripe account detail",
 *     description="Retrieve details of a specific Stripe account. Requires 'find-stripe-account' permission.",
 *     operationId="getStripeAccountDetail",
 *     tags={"Stripeアカウント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="1")
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
class GetStripeAccountDetailAction {}
