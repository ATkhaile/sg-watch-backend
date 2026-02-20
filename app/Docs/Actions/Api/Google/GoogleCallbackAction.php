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
 *     path="/api/v1/login/google",
 *     summary="Google OAuth callback",
 *     description="Handle Google OAuth callback with authorization code. Authenticates user via Google Sign In.",
 *     operationId="googleCallback",
 *     tags={"Google OAuth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"code"},
 *             @OA\Property(property="code", type="string", description="Authorization code from Google", example="4/0AY0e-g7xxxxxxxxxxxxx"),
 *             @OA\Property(property="type", type="string", nullable=true, description="Role type (from Role enum)", example="user")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GoogleCallbackAction {}
