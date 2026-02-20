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
 *     path="/api/v1/authorization/roles/{role_id}/permissions",
 *     summary="Revoke permissions from a role",
 *     description="Remove one or more permissions from a specific role. Requires revoke-permission-to-role permission.",
 *     operationId="revokePermissionToRole",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="role_id",
 *         in="path",
 *         description="Role ID to revoke permissions from",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Array of permission IDs to revoke",
 *         @OA\JsonContent(
 *             required={"permission_ids"},
 *             @OA\Property(
 *                 property="permission_ids",
 *                 type="array",
 *                 description="Array of permission IDs to remove from the role",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2, 3}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permissions revoked successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Permissions revoked from role successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="role_id", type="integer", example=1),
 *                 @OA\Property(property="role_name", type="string", example="content-manager"),
 *                 @OA\Property(
 *                     property="revoked_permissions",
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
 *         description="Forbidden - User doesn't have revoke-permission-to-role permission",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="This action is unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Role not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Role not found")
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
 *                     property="permission_ids",
 *                     type="array",
 *                     @OA\Items(type="string", example="The permission ids field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class RevokePermissionToRoleAction {}
