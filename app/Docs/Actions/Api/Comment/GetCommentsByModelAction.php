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
 *     path="/api/v1/comments/{model}/{modelId}",
 *     summary="Get comments by model",
 *     description="Retrieve comments for a specific model and model ID with pagination and sorting.",
 *     operationId="getCommentsByModel",
 *     tags={"コメント"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="model", in="path", required=true, @OA\Schema(type="string", enum={"columns", "news"}, example="columns")),
 *     @OA\Parameter(name="modelId", in="path", required=true, @OA\Schema(type="integer", example=1)),
 *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", minimum=1, example=1)),
 *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", minimum=1, example=10)),
 *     @OA\Parameter(name="sort_created", in="query", required=false, @OA\Schema(type="string", enum={"ASC", "DESC"}, example="DESC")),
 *     @OA\Parameter(name="sort_updated", in="query", required=false, @OA\Schema(type="string", enum={"ASC", "DESC"}, example="DESC")),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class GetCommentsByModelAction {}
