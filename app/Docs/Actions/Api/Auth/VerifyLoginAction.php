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
 *     path="/api/v1/verify-login",
 *     summary="Verify login with two-factor code",
 *     description="Verify user login with two-factor authentication code. After initial login attempt, a 6-digit verification code is sent to the user. This endpoint validates the code and returns JWT token upon success.",
 *     operationId="verifyLogin",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Verification data",
 *         @OA\JsonContent(
 *             required={"verification_code"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 maxLength=255,
 *                 description="User email address (required if user_id not provided)",
 *                 example="user@example.com"
 *             ),
 *             @OA\Property(
 *                 property="user_id",
 *                 type="string",
 *                 maxLength=255,
 *                 description="User ID (required if email not provided)",
 *                 example="USR123456"
 *             ),
 *             @OA\Property(
 *                 property="verification_code",
 *                 type="string",
 *                 minLength=6,
 *                 maxLength=6,
 *                 description="6-digit verification code sent to user",
 *                 example="123456"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Verification successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login verified successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *                 @OA\Property(property="token_type", type="string", example="bearer"),
 *                 @OA\Property(property="expires_in", type="integer", example=3600),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="string", example="USR123456"),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="user@example.com"),
 *                     @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Invalid verification code",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid verification code")
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
 *                     @OA\Items(type="string", example="The email field is required when user_id is not present.")
 *                 ),
 *                 @OA\Property(
 *                     property="verification_code",
 *                     type="array",
 *                     @OA\Items(type="string", example="The verification code must be 6 characters.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class VerifyLoginAction {}
