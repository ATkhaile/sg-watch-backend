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
 *     path="/api/v1/forgot-password",
 *     summary="Request password reset",
 *     description="Send password reset link to user's email address. The email must exist in the system. A reset token will be sent to the email for use in the password reset process.",
 *     operationId="forgotPassword",
 *     tags={"認証"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Email address for password reset",
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 description="User email address (must exist in system)",
 *                 example="user@example.com"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset email sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Password reset link has been sent to your email"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="email", type="string", example="user@example.com")
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
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email field is required.")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Email not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="We couldn't find a user with that email address.")
 *         )
 *     )
 * )
 */
class ForgotPasswordAction {}
