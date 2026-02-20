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
 *     path="/api/v1/authorization/users/{user_id}/roles/attach",
 *     summary="Attach roles to a user",
 *     description="Assign one or more roles to a specific user. Requires attach-role-to-user permission.",
 *     operationId="attachRoleToUser",
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
 *         description="Array of role IDs to attach",
 *         @OA\JsonContent(
 *             required={"role_ids"},
 *             @OA\Property(
 *                 property="role_ids",
 *                 type="array",
 *                 description="Array of role IDs (each must exist in roles table)",
 *                 @OA\Items(type="integer"),
 *                 example={1, 2, 3}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Roles attached successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Roles attached to user successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(
 *                     property="attached_roles",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="content-manager"),
 *                         @OA\Property(property="display_name", type="string", example="Content Manager")
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
 *         description="Forbidden - User doesn't have attach-role-to-user permission",
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
 *                     property="role_ids",
 *                     type="array",
 *                     @OA\Items(type="string", example="The role ids field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="role_ids.0",
 *                     type="array",
 *                     @OA\Items(type="string", example="The selected role ids.0 is invalid.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class AttachRoleToUserAction {}
