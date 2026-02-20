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
 *     path="/api/v1/user-info",
 *     summary="Get current user information",
 *     description="Retrieve detailed information about the currently authenticated user. Requires a valid JWT bearer token in the Authorization header.",
 *     operationId="getUserInfo",
 *     tags={"認証"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User information retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User information retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="string", example="USR123456"),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="user@example.com"),
 *                 @OA\Property(property="avatar", type="string", nullable=true, example="https://example.com/storage/avatars/user123.jpg"),
 *                 @OA\Property(property="invite_code", type="string", nullable=true, example="INVITE2024"),
 *                 @OA\Property(property="email_verified_at", type="string", format="datetime", nullable=true, example="2024-01-15T10:00:00Z"),
 *                 @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="datetime", example="2024-01-20T15:30:00Z"),
 *                 @OA\Property(
 *                     property="roles",
 *                     type="array",
 *                     description="User roles",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="user")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="permissions",
 *                     type="array",
 *                     description="User permissions",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="view-dashboard")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Invalid or missing token",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */
class UserInfoAction {}
