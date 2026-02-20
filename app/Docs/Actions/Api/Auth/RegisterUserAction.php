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
 *     path="/api/v1/register",
 *     summary="Register new user",
 *     description="Create a new user account with first name, last name, email, and password. Optionally accepts invite code for referral tracking.",
 *     operationId="registerUser",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="User registration data",
 *         @OA\JsonContent(
 *             required={"first_name", "last_name", "email", "password", "password_confirmation"},
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
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 maxLength=255,
 *                 description="User email address (must be unique)",
 *                 example="john.doe@example.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="User password (8-16 characters, must be confirmed)",
 *                 example="SecurePass123"
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="Password confirmation (must match password)",
 *                 example="SecurePass123"
 *             ),
 *             @OA\Property(
 *                 property="invite_code",
 *                 type="string",
 *                 maxLength=255,
 *                 description="Referral invite code from existing user (optional)",
 *                 example="INVITE2024"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="User registered successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="first_name", type="string", example="John"),
 *                 @OA\Property(property="last_name", type="string", example="Doe"),
 *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *                 @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
 *             )
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
 *                     property="first_name",
 *                     type="array",
 *                     @OA\Items(type="string", example="The first name field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="last_name",
 *                     type="array",
 *                     @OA\Items(type="string", example="The last name field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email has already been taken.")
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password must be at least 8 characters.")
 *                 ),
 *                 @OA\Property(
 *                     property="password_confirmation",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password confirmation does not match.")
 *                 ),
 *                 @OA\Property(
 *                     property="invite_code",
 *                     type="array",
 *                     @OA\Items(type="string", example="The selected invite code is invalid.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class RegisterUserAction {}
