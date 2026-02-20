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
 *     path="/api/v1/users/{id}",
 *     summary="Get user detail",
 *     description="Retrieve details of a specific user. Requires 'find-users' permission.",
 *     operationId="getUserDetail",
 *     tags={"ユーザー"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
class GetUsersDetailAction {}
