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
 *     path="/api/v1/update-avatar",
 *     summary="Update user avatar",
 *     description="Upload and update the authenticated user's avatar image. Accepts jpeg, png, jpg, or gif files with maximum size of 100MB.",
 *     operationId="updateAvatar",
 *     tags={"認証"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Avatar image file",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"avatar"},
 *                 @OA\Property(
 *                     property="avatar",
 *                     type="string",
 *                     format="binary",
 *                     description="Avatar image file (jpeg, jpg, png, gif - max 100MB)"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Avatar updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Avatar updated successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="string", example="USR123456"),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", example="user@example.com"),
 *                 @OA\Property(property="avatar", type="string", example="https://example.com/storage/avatars/user123.jpg"),
 *                 @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="datetime", example="2024-01-20T15:30:00Z")
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
 *                     property="avatar",
 *                     type="array",
 *                     @OA\Items(type="string", example="The avatar field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class UpdateAvatarAction {}
