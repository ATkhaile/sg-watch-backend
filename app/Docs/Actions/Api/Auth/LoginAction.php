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
 *     path="/api/v1/login",
 *     summary="User login",
 *     description="Authenticate user with email or user_id and password. Returns JWT token for accessing protected endpoints. Optionally accepts verification code for two-factor authentication.",
 *     operationId="login",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Login credentials",
 *         @OA\JsonContent(
 *             required={"password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 description="User email address (required if user_id not provided)",
 *                 example="user@example.com"
 *             ),
 *             @OA\Property(
 *                 property="user_id",
 *                 type="string",
 *                 description="User ID (required if email not provided)",
 *                 example="USR123456"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 description="User password",
 *                 example="MyPassword123"
 *             ),
 *             @OA\Property(
 *                 property="verification_code",
 *                 type="string",
 *                 description="Two-factor authentication code (optional)",
 *                 example="123456"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login successful"),
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
 *         description="Unauthorized - Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid credentials")
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
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class LoginAction {}
