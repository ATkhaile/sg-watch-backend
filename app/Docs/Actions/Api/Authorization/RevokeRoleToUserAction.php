<?php

namespace App\Docs\Actions\Api\Authorization;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="認可",
 *     description="Authorization"
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/authorization/users/{user_id}/roles",
 *     summary="Revoke roles from a user",
 *     description="Remove one or more roles from a specific user. Requires revoke-role-to-user permission.",
 *     operationId="revokeRoleToUser",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         description="User ID to revoke roles from",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Array of role IDs to revoke",
 *         @OA\JsonContent(
 *             required={"role_ids"},
 *             @OA\Property(
 *                 property="role_ids",
 *                 type="array",
 *                 description="Array of role IDs to remove from the user",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2, 3}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Roles revoked successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Roles revoked from user successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(
 *                     property="revoked_roles",
 *                     type="array",
 *                     @OA\Items(type="integer"),
 *                     example={1, 2, 3}
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
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden - User doesn't have revoke-role-to-user permission",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="This action is unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="User not found")
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
 *                     property="role_ids",
 *                     type="array",
 *                     @OA\Items(type="string", example="The role ids field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class RevokeRoleToUserAction {}
