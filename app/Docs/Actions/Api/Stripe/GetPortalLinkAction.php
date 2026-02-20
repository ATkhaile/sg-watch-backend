<?php

namespace App\Docs\Actions\Api\Stripe;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripe",
 *     description="Stripe"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/stripe/portal-link",
 *     summary="Get Stripe portal link",
 *     description="Get Stripe customer portal link for the authenticated user to manage their subscription. Requires authentication.",
 *     operationId="getStripePortalLink",
 *     tags={"Stripe"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="url", type="string", example="https://billing.stripe.com/session/...")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=404, description="Customer not found"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
class GetPortalLinkAction {}
