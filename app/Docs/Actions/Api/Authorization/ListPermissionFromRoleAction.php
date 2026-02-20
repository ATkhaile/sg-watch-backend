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
 *     path="/api/v1/authorization/roles/{role_id}/permissions",
 *     summary="List permissions assigned to a role",
 *     description="Retrieve all permissions associated with a specific role with pagination and sorting. Requires list-permission-from-role permission.",
 *     operationId="listPermissionFromRole",
 *     tags={"認可"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="role_id",
 *         in="path",
 *         description="Role ID",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
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
 *         description="Sort by permission name",
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
 *             @OA\Property(property="message", type="string", example="Permissions retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="role",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="content-manager"),
 *                     @OA\Property(property="display_name", type="string", example="Content Manager")
 *                 ),
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="create-news"),
 *                         @OA\Property(property="display_name", type="string", example="Create News"),
 *                         @OA\Property(property="description", type="string", example="Permission to create news articles"),
 *                         @OA\Property(property="domain", type="string", example="news"),
 *                         @OA\Property(property="created_at", type="string", format="datetime", example="2024-01-15T10:00:00Z")
 *                     )
 *                 ),
 *                 @OA\Property(property="current_page", type="integer", example=1),
 *                 @OA\Property(property="per_page", type="integer", example=10),
 *                 @OA\Property(property="total", type="integer", example=25),
 *                 @OA\Property(property="last_page", type="integer", example=3)
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
 *         description="Forbidden - User doesn't have list-permission-from-role permission",
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
 *             @OA\Property(property="message", type="string", example="The given data was invalid.")
 *         )
 *     )
 * )
 */
class ListPermissionFromRoleAction {}
