<?php

namespace App\Docs\Actions\Api\Users;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="ユーザー",
 *     description="Users"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/users/create",
 *     summary="Create user",
 *     description="Create a new user with first name, last name, email, and password. Email must be unique. Password must be at least 8 characters. Requires 'create-users' permission.",
 *     operationId="createUser",
 *     tags={"ユーザー"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "password"},
 *             @OA\Property(property="first_name", type="string", maxLength=50, example="John", description="User first name"),
 *             @OA\Property(property="last_name", type="string", maxLength=50, example="Doe", description="User last name"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Unique email address"),
 *             @OA\Property(property="password", type="string", format="password", minLength=8, example="password123", description="Minimum 8 characters")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class CreateUsersAction {}
