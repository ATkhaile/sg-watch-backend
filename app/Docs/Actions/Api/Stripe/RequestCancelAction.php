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
 *     path="/api/v1/stripe/cancel-request",
 *     summary="Request subscription cancellation",
 *     description="Submit a request to cancel Stripe subscription. A cancellation code will be sent for verification. Requires authentication.",
 *     operationId="requestStripeCancellation",
 *     tags={"Stripe"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Cancellation code sent")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=404, description="Subscription not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class RequestCancelAction {}
