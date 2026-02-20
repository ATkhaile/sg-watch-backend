<?php

namespace App\Docs\Actions\Api\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v1/password/otp/send",
 *     summary="Send password reset OTP",
 *     description="Send a 6-digit OTP to the user's email for password reset. Always returns success to prevent email enumeration.",
 *     operationId="sendPasswordOtp",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Email address to send OTP",
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 description="User email address",
 *                 example="user@example.com"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent (always returns success)",
 *         @OA\JsonContent(
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="If the email exists, a verification code has been sent.")
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
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class SendPasswordOtpAction {}
