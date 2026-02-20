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
 *     path="/api/v1/authorization/permissions/{id}",
 *     summary="Update permission details",
 *     description="Update an existing permission's name, display name, description, or domain. Requires update-permission permission.",
 *     operationId="updatePermissionDetail",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Permission ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         description="Permission data to update (all fields are optional)",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=255,
 *                 nullable=true,
 *                 description="Unique permission name (no spaces allowed)",
 *                 example="edit-news"
 *             ),
 *             @OA\Property(
 *                 property="display_name",
 *                 type="string",
 *                 maxLength=255,
 *                 nullable=true,
 *                 description="Human-readable display name for the permission",
 *                 example="Edit News"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 nullable=true,
 *                 description="Description of what this permission allows",
 *                 example="Allows user to edit existing news articles"
 *             ),
 *             @OA\Property(
 *                 property="domain",
 *                 type="string",
 *                 nullable=true,
 *                 description="Domain or module this permission belongs to",
 *                 example="news"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permission updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Permission updated successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="edit-news"),
 *                 @OA\Property(property="display_name", type="string", example="Edit News"),
 *                 @OA\Property(property="description", type="string", example="Allows user to edit existing news articles"),
 *                 @OA\Property(property="domain", type="string", example="news"),
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
 *         description="Forbidden - User doesn't have update-permission permission",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="This action is unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Permission not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Permission not found")
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
class UpdatePermissionDetailAction {}
