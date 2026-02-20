<?php

namespace App\Docs\Actions\Api\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="認証",
 *     description="Auth"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/check-reset-token/{token}",
 *     summary="Check password reset token validity",
 *     description="Validates if a password reset token is still valid, has not expired, and can be used to reset a password. This endpoint does not require authentication.",
 *     operationId="checkResetToken",
 *     tags={"認証"},
 *     @OA\Parameter(
 *         name="token",
 *         in="path",
 *         required=true,
 *         description="Password reset token to validate",
 *         @OA\Schema(type="string", example="abcdef123456789token")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Token validation result",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Token is valid"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="valid", type="boolean", example=true),
 *                 @OA\Property(property="status", type="string", example="valid", description="Status: valid, invalid, or expired"),
 *                 @OA\Property(property="email", type="string", example="user@example.com")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Token is invalid or expired",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Token is invalid or expired"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="valid", type="boolean", example=false),
 *                 @OA\Property(property="status", type="string", example="expired", description="Status: valid, invalid, or expired")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="token",
 *                     type="array",
 *                     @OA\Items(type="string", example="The token field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class CheckResetTokenAction {}
