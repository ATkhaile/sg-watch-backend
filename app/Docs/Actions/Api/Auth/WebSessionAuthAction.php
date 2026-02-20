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
 *     path="/api/v1/web-session",
 *     summary="Authenticate web session",
 *     description="Convert a web session token into a JWT token for API access. This endpoint bridges web-based authentication with API token authentication.",
 *     operationId="webSessionAuth",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Web session credentials",
 *         @OA\JsonContent(
 *             required={"session_token"},
 *             @OA\Property(
 *                 property="session_token",
 *                 type="string",
 *                 description="Valid web session token",
 *                 example="web_sess_abc123def456ghi789"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Web session authenticated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Authentication successful"),
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
 *                     @OA\Property(property="avatar", type="string", nullable=true, example="https://example.com/storage/avatars/user123.jpg"),
 *                     @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Invalid or expired session token",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid or expired session token")
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
 *                     property="session_token",
 *                     type="array",
 *                     @OA\Items(type="string", example="The session_token field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class WebSessionAuthAction {}
