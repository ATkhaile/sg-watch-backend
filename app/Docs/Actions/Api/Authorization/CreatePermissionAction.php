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
 *     path="/api/v1/authorization/permissions",
 *     summary="Create a new permission",
 *     description="Create a new permission with name, display name, domain, and optional description. Requires create-permission permission.",
 *     operationId="createPermission",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Permission data",
 *         @OA\JsonContent(
 *             required={"name", "display_name", "domain"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=255,
 *                 description="Unique permission name (no spaces allowed)",
 *                 example="create-news"
 *             ),
 *             @OA\Property(
 *                 property="display_name",
 *                 type="string",
 *                 maxLength=255,
 *                 description="Human-readable display name for the permission",
 *                 example="Create News"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 nullable=true,
 *                 description="Optional description of what this permission allows",
 *                 example="Allows user to create and publish news articles"
 *             ),
 *             @OA\Property(
 *                 property="domain",
 *                 type="string",
 *                 description="Domain or module this permission belongs to",
 *                 example="news"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permission created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Permission created successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="create-news"),
 *                 @OA\Property(property="display_name", type="string", example="Create News"),
 *                 @OA\Property(property="description", type="string", example="Allows user to create and publish news articles"),
 *                 @OA\Property(property="domain", type="string", example="news"),
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
 *         description="Forbidden - User doesn't have create-permission permission",
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
 *                 ),
 *                 @OA\Property(
 *                     property="domain",
 *                     type="array",
 *                     @OA\Items(type="string", example="The domain field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class CreatePermissionAction {}
