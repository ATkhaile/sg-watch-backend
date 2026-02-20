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
 *     path="/api/v1/authorization/users/{user_id}/permissions/attach",
 *     summary="Attach permissions to a user",
 *     description="Assign one or more permissions directly to a specific user. Requires attach-permission-to-user permission.",
 *     operationId="attachPermissionToUser",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         description="User ID (must exist in users table)",
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
 *             @OA\Property(property="message", type="string", example="Permissions attached to user successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user_id", type="integer", example=1),
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
 *         description="Forbidden - User doesn't have attach-permission-to-user permission",
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
 *                     property="user_id",
 *                     type="array",
 *                     @OA\Items(type="string", example="The selected user id is invalid.")
 *                 ),
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
class AttachPermissionToUserAction {}
