<?php

namespace App\Docs\Actions\Api\Chat;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="チャット - ユーザー間",
 *     description="Chat - User to User"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/chat/users",
 *     summary="Get available users",
 *     description="Retrieve a list of users available for chat interactions.",
 *     operationId="getAvailableUsers",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class GetAvailableUsersAction {}
