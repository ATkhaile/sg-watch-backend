<?php

namespace App\Docs\Actions\Api\Users;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="ユーザー",
 *     description="Users"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/users/list",
 *     summary="Get all users",
 *     description="Retrieve a paginated list of users with search and sorting options. Supports filtering by email with like/not operators, general text search across first name, last name, and email. Requires 'list-users' permission.",
 *     operationId="getAllUsers",
 *     tags={"ユーザー"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", maxLength=255, description="General search across first name, last name, and email")
 *     ),
 *     @OA\Parameter(
 *         name="search_email",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", maxLength=256, description="Search by email")
 *     ),
 *     @OA\Parameter(
 *         name="search_email_like",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="boolean", description="Use LIKE search for email")
 *     ),
 *     @OA\Parameter(
 *         name="search_email_not",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="boolean", description="Negate email search")
 *     ),
 *     @OA\Parameter(
 *         name="admin",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="boolean", description="Filter by admin status")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, example=10)
 *     ),
 *     @OA\Parameter(
 *         name="sort_first_name",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by first name")
 *     ),
 *     @OA\Parameter(
 *         name="sort_email",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by email")
 *     ),
 *     @OA\Parameter(
 *         name="sort_created",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by creation date")
 *     ),
 *     @OA\Parameter(
 *         name="sort_updated",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", enum={"ASC", "DESC"}, description="Sort by update date")
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetAllUsersAction {}
