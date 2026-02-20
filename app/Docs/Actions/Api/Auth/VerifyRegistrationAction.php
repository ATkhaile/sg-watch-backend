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
 *     path="/api/v1/verify-registration/{token}",
 *     summary="Verify user registration",
 *     description="Verify a user's email address after registration using either a verification token or a 6-digit verification code. This endpoint does not require authentication. May return an auto-login token upon successful verification.",
 *     operationId="verifyRegistration",
 *     tags={"認証"},
 *     @OA\Parameter(
 *         name="token",
 *         in="path",
 *         required=true,
 *         description="Verification token from email",
 *         @OA\Schema(type="string", example="abcdef123456789verificationtoken")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Email verified successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Email verified successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="verified", type="boolean", example=true),
 *                 @OA\Property(property="access_token", type="string", nullable=true, example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *                 @OA\Property(property="token_type", type="string", nullable=true, example="bearer"),
 *                 @OA\Property(property="expires_in", type="integer", nullable=true, example=3600),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="string", example="USR123456"),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="email_verified_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired verification code/token",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid or expired verification code")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="User not found")
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
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="token",
 *                     type="array",
 *                     @OA\Items(type="string", example="Either token or verification_code is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class VerifyRegistrationAction {}
