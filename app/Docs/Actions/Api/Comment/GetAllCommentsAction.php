<?php

namespace App\Docs\Actions\Api\Comment;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="コメント",
 *     description="Comment"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/comments/list",
 *     summary="Get all comments (Admin)",
 *     description="Retrieve all comments across all models for admin management.",
 *     operationId="getAllComments",
 *     tags={"コメント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class GetAllCommentsAction {}
