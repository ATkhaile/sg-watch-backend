<?php

namespace App\Docs\Actions\Api\Users;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="ユーザー",
 *     description="Users"
 * )
 *
 * @OA\Put(
 *     path="/api/v1/users/{id}",
 *     summary="Update user",
 *     description="Update an existing user. All fields are optional. Email must remain unique. Password must be at least 8 characters if provided. Requires 'update-users' permission.",
 *     operationId="updateUser",
 *     tags={"ユーザー"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="first_name", type="string", maxLength=50, nullable=true, example="John", description="User first name"),
 *             @OA\Property(property="last_name", type="string", maxLength=50, nullable=true, example="Doe", description="User last name"),
 *             @OA\Property(property="email", type="string", format="email", nullable=true, example="johnupdated@example.com", description="Unique email address"),
 *             @OA\Property(property="gender", type="string", nullable=true, enum={"male", "female", "other", "unknown"}, example="male", description="User gender"),
 *             @OA\Property(property="password", type="string", format="password", minLength=8, nullable=true, example="newpassword123", description="Minimum 8 characters")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Not found"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class UpdateUsersDetailAction {}
