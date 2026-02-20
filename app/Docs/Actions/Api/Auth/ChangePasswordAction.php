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
 *     path="/api/v1/change-password",
 *     summary="Change user password",
 *     description="Change the password for the currently authenticated user. Requires the old password for verification and a new password that must be confirmed. Both passwords must be 8-16 characters.",
 *     operationId="changePassword",
 *     tags={"認証"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Password change data",
 *         @OA\JsonContent(
 *             required={"password_old", "password", "password_confirmation"},
 *             @OA\Property(
 *                 property="password_old",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="Current password for verification (8-16 characters)",
 *                 example="OldPassword123"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="New password (8-16 characters, must be confirmed)",
 *                 example="NewPassword456"
 *             ),
 *             @OA\Property(
 *                 property="password_confirmation",
 *                 type="string",
 *                 format="password",
 *                 minLength=8,
 *                 maxLength=16,
 *                 description="New password confirmation (must match password)",
 *                 example="NewPassword456"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password changed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Your password has been changed successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Invalid or missing token, or incorrect old password",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="The old password is incorrect")
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
 *                     property="password_old",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password old field is required.")
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
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class ChangePasswordAction {}
