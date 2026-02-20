<?php

namespace App\Docs\Actions\Api\Authorization;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="認可",
 *     description="Authorization"
 * )
 *
 * @OA\Put(
 *     path="/api/v1/authorization/roles/{id}",
 *     summary="Update role details",
 *     description="Update an existing role's name, display name, or description. Requires update-role permission.",
 *     operationId="updateRoleDetail",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Role ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         description="Role data to update (all fields are optional)",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=255,
 *                 nullable=true,
 *                 description="Unique role name (no spaces allowed)",
 *                 example="content-editor"
 *             ),
 *             @OA\Property(
 *                 property="display_name",
 *                 type="string",
 *                 maxLength=255,
 *                 nullable=true,
 *                 description="Human-readable display name for the role",
 *                 example="Content Editor"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 nullable=true,
 *                 description="Description of the role's purpose and responsibilities",
 *                 example="Edits and publishes content"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Role updated successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="content-editor"),
 *                 @OA\Property(property="display_name", type="string", example="Content Editor"),
 *                 @OA\Property(property="description", type="string", example="Edits and publishes content"),
 *                 @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="datetime", example="2024-01-15T11:00:00Z")
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
 *         description="Forbidden - User doesn't have update-role permission",
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
 *                     property="name",
 *                     type="array",
 *                     description="Possible validation errors for name field",
 *                     @OA\Items(type="string"),
 *                     example={"The name must not contain spaces.", "The name has already been taken."}
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class UpdateRoleDetailAction {}
