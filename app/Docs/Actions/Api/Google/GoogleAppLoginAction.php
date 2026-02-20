<?php

namespace App\Docs\Actions\Api\Google;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Google OAuth",
 *     description="Google OAuth"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/login/google-app",
 *     summary="Google app login",
 *     description="Authenticate user using Google ID token from mobile app.",
 *     operationId="googleAppLogin",
 *     tags={"Google OAuth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"token"},
 *             @OA\Property(property="token", type="string", description="Google ID token", example="eyJhbGciOiJSUzI1NiIsImtpZCI6..."),
 *             @OA\Property(property="type", type="string", nullable=true, description="Role type (from Role enum)", example="user")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GoogleAppLoginAction {}
