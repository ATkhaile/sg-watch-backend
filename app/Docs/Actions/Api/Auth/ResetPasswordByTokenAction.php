<?php

namespace App\Docs\Actions\Api\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v1/password/reset",
 *     summary="Reset password with token",
 *     description="Reset user password using the reset_token obtained from OTP verification step.",
 *     operationId="resetPasswordByToken",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Reset token and new password",
 *         @OA\JsonContent(
 *             required={"reset_token", "password", "password_confirmation"},
 *             @OA\Property(
 *                 property="reset_token",
 *                 type="string",
 *                 description="Reset token from OTP verification",
 *                 example="rt_abc123def456..."
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 description="New password (8-16 characters)",
 *                 example="NewPass123"
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 description="Password confirmation (must match password)",
 *                 example="NewPass123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Password has been reset")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired reset token",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="Password must be at least 8 characters")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class ResetPasswordByTokenAction {}
