<?php

namespace App\Docs\Actions\Api\Auth;

use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/v1/member/update-profile",
 *     summary="Update own profile",
 *     description="Update the authenticated user's profile information. All fields are optional - only provided fields will be updated.",
 *     operationId="updateProfile",
 *     tags={"認証"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Profile fields to update (all optional)",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="first_name",
 *                 type="string",
 *                 maxLength=50,
 *                 description="User first name",
 *                 example="John"
 *             ),
 *             @OA\Property(
 *                 property="last_name",
 *                 type="string",
 *                 maxLength=50,
 *                 description="User last name",
 *                 example="Doe"
 *             ),
 *             @OA\Property(
 *                 property="gender",
 *                 type="string",
 *                 enum={"male", "female", "other", "unknown"},
 *                 nullable=true,
 *                 description="User gender",
 *                 example="male"
 *             ),
 *             @OA\Property(
 *                 property="birthday",
 *                 type="string",
 *                 format="date",
 *                 nullable=true,
 *                 description="User birthday (YYYY-MM-DD format)",
 *                 example="1990-01-15"
 *             ),
 *             @OA\Property(
 *                 property="avatar_url",
 *                 type="string",
 *                 maxLength=500,
 *                 nullable=true,
 *                 description="User avatar URL path",
 *                 example="avatars/1/photo.jpg"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
 *             @OA\Property(property="status_code", type="integer", example=200),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="string", example="1"),
 *                     @OA\Property(property="first_name", type="string", example="John"),
 *                     @OA\Property(property="last_name", type="string", example="Doe"),
 *                     @OA\Property(property="avatar_url", type="string", example="https://example.com/avatars/1/photo.jpg"),
 *                     @OA\Property(property="gender", type="string", example="male"),
 *                     @OA\Property(property="birthday", type="string", format="date", example="1990-01-15"),
 *                     @OA\Property(property="role", type="string", example="user"),
 *                     @OA\Property(property="is_admin", type="boolean", example=false),
 *                     @OA\Property(property="email", type="string", example="john@example.com"),
 *                     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *                     @OA\Property(property="entitlements", type="object")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
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
 *                     property="gender",
 *                     type="array",
 *                     @OA\Items(type="string", example="Gender must be one of: male, female, other, unknown")
 *                 ),
 *                 @OA\Property(
 *                     property="birthday",
 *                     type="array",
 *                     @OA\Items(type="string", example="Birthday must be in YYYY-MM-DD format")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class UpdateProfileAction {}
