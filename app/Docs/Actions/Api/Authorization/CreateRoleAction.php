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
 *     path="/api/v1/authorization/roles/create",
 *     summary="Create a new role",
 *     description="Create a new role with name, display name, and optional description. Requires create-role permission.",
 *     operationId="createRole",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Role data",
 *         @OA\JsonContent(
 *             required={"name", "display_name"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=255,
 *                 description="Unique role name (no spaces allowed)",
 *                 example="content-manager"
 *             ),
 *             @OA\Property(
 *                 property="display_name",
 *                 type="string",
 *                 maxLength=255,
 *                 description="Human-readable display name for the role",
 *                 example="Content Manager"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 nullable=true,
 *                 description="Optional description of the role's purpose and responsibilities",
 *                 example="Manages all content-related operations including news and media"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Role created successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="content-manager"),
 *                 @OA\Property(property="display_name", type="string", example="Content Manager"),
 *                 @OA\Property(property="description", type="string", example="Manages all content-related operations"),
 *                 @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z"),
 *                 @OA\Property(property="updated_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
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
 *         description="Forbidden - User doesn't have create-role permission",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="This action is unauthorized")
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
 *                     example={"The name field is required.", "The name must not contain spaces.", "The name has already been taken."}
 *                 ),
 *                 @OA\Property(
 *                     property="display_name",
 *                     type="array",
 *                     @OA\Items(type="string", example="The display name field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class CreateRoleAction {}
