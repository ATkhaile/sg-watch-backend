<?php

namespace App\Docs\Actions\Api\Authorization;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="認可",
 *     description="Authorization"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/authorization/roles/list",
 *     summary="Get all roles with filters and pagination",
 *     description="Retrieve a paginated list of roles with search and sorting options. Requires list-role permission.",
 *     operationId="getAllRoles",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search keyword for role name and display name",
 *         required=false,
 *         @OA\Schema(type="string", maxLength=256, example="manager")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number for pagination",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Number of items per page",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=10)
 *     ),
 *     @OA\Parameter(
 *         name="sort_name",
 *         in="query",
 *         description="Sort by role name",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, example="ASC")
 *     ),
 *     @OA\Parameter(
 *         name="sort_created",
 *         in="query",
 *         description="Sort by creation date",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, example="DESC")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Roles retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="content-manager"),
 *                         @OA\Property(property="display_name", type="string", example="Content Manager"),
 *                         @OA\Property(property="description", type="string", example="Manages all content-related operations"),
 *                         @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z"),
 *                         @OA\Property(property="updated_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
 *                     )
 *                 ),
 *                 @OA\Property(property="current_page", type="integer", example=1),
 *                 @OA\Property(property="per_page", type="integer", example=10),
 *                 @OA\Property(property="total", type="integer", example=50),
 *                 @OA\Property(property="last_page", type="integer", example=5)
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
 *         description="Forbidden - User doesn't have list-role permission",
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
 *                     property="page",
 *                     type="array",
 *                     @OA\Items(type="string", example="The page must be at least 1.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class GetAllRoleAction {}
