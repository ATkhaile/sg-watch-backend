<?php

namespace App\Docs\Actions\Api\Authorization;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="認可",
 *     description="Authorization"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/authorization/roles/{role_id}/permissions",
 *     summary="Attach permissions to a role",
 *     description="Assign one or more permissions to a specific role. Requires attach-permission-to-role permission.",
 *     operationId="attachPermissionToRole",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="role_id",
 *         in="path",
 *         description="Role ID to attach permissions to",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Array of permission IDs to attach",
 *         @OA\JsonContent(
 *             required={"permission_ids"},
 *             @OA\Property(
 *                 property="permission_ids",
 *                 type="array",
 *                 description="Array of permission IDs (each must exist in permissions table)",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2, 3, 4}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permissions attached successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Permissions attached to role successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="role_id", type="integer", example=1),
 *                 @OA\Property(property="role_name", type="string", example="content-manager"),
 *                 @OA\Property(
 *                     property="attached_permissions",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="create-news"),
 *                         @OA\Property(property="display_name", type="string", example="Create News"),
 *                         @OA\Property(property="domain", type="string", example="news")
 *                     )
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
 *         description="Forbidden - User doesn't have attach-permission-to-role permission",
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
 *                 ),
 *                 @OA\Property(
 *                     property="permission_ids.0",
 *                     type="array",
 *                     @OA\Items(type="string", example="The selected permission ids.0 is invalid.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class AttachPermissionToRoleAction {}
