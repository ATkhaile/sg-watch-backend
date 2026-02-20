<?php

namespace App\Docs\Actions\Api\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="認証",
 *     description="Auth"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/reset-password/{token}",
 *     summary="Reset user password",
 *     description="Reset user password using the token received via email from forgot-password endpoint. The new password must be 8-16 characters and confirmed.",
 *     operationId="resetPassword",
 *     tags={"認証"},
 *     @OA\Parameter(
 *         name="token",
 *         in="path",
 *         required=true,
 *         description="Password reset token from email",
 *         @OA\Schema(type="string", example="abc123def456ghi789jkl012mno345pqr678stu901vwx234yz")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="New password data",
 *         @OA\JsonContent(
 *             required={"password", "password_confirmation"},
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="New password (8-16 characters, must be confirmed)",
 *                 example="NewSecurePass123"
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="Password confirmation (must match password)",
 *                 example="NewSecurePass123"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Your password has been reset successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired token",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="This password reset token is invalid or has expired")
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
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password must be at least 8 characters.")
 *                 ),
 *                 @OA\Property(
 *                     property="password_confirmation",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password confirmation does not match.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class ResetPasswordAction {}
