<?php

namespace App\Docs\Actions\Api\Comment;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="コメント",
 *     description="Comment"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/comments/create",
 *     summary="Create a comment",
 *     description="Create a comment on a specific model (columns or news).",
 *     operationId="createComment",
 *     tags={"コメント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"model", "model_id", "content"},
 *             @OA\Property(property="model", type="string", enum={"columns", "news"}, example="columns", description="Model type to comment on"),
 *             @OA\Property(property="model_id", type="integer", minimum=1, example=1, description="ID of the model"),
 *             @OA\Property(property="content", type="string", maxLength=5000, example="This is a great article!")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class CreateCommentAction {}
