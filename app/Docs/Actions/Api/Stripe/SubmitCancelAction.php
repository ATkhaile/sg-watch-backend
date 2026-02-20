<?php

namespace App\Docs\Actions\Api\Stripe;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Stripe",
 *     description="Stripe"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/stripe/submit-request",
 *     summary="Submit cancellation request",
 *     description="Submit the final cancellation request after code verification to cancel the Stripe subscription. Public endpoint (no authentication required).",
 *     operationId="submitStripeCancellation",
 *     tags={"Stripe"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"code"},
 *             @OA\Property(property="code", type="string", example="123456", description="Verified cancellation code")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Subscription cancelled successfully")
 *         )
 *     ),
 *     @OA\Response(response=400, description="Invalid code"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class SubmitCancelAction {}
