<?php

namespace App\Docs\Actions\Api\Comment;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="コメント",
 *     description="Comment"
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/comments/{id}",
 *     summary="Delete comment (Admin)",
 *     description="Delete a comment by ID. Admin only.",
 *     operationId="deleteComment",
 *     tags={"コメント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
 *     @OA\Response(response=200, description="Deleted successfully"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
class DeleteCommentAction {}
