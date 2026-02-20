<?php

namespace App\Docs\Actions\Api\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v1/password/otp/verify",
 *     summary="Verify password reset OTP",
 *     description="Verify the 6-digit OTP sent to email. Returns a reset_token on success for use in the password reset step. Maximum 5 attempts per OTP.",
 *     operationId="verifyPasswordOtp",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Email and OTP code",
 *         @OA\JsonContent(
 *             required={"email", "otp"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 description="User email address",
 *                 example="user@example.com"
 *             ),
 *             @OA\Property(
 *                 property="otp",
 *                 type="string",
 *                 description="6-digit OTP code",
 *                 example="352325",
 *                 minLength=6,
 *                 maxLength=6
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Verification successful."),
 *             @OA\Property(property="reset_token", type="string", example="rt_abc123def456...")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired OTP",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=400),
 *             @OA\Property(property="message", type="string", example="Invalid or expired verification code.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="Too many attempts",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=429),
 *             @OA\Property(property="message", type="string", example="Too many attempts. Please request a new code.")
 *         )
 *     )
 * )
 */
class VerifyPasswordOtpAction {}
