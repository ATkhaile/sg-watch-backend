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
 *     path="/api/v1/stripe/check-code-request",
 *     summary="Check cancellation code",
 *     description="Verify the cancellation code sent to user. Public endpoint (no authentication required).",
 *     operationId="checkStripeCancelCode",
 *     tags={"Stripe"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"code"},
 *             @OA\Property(property="code", type="string", example="123456", description="Cancellation verification code")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="valid", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class CheckCancelCodeAction {}
