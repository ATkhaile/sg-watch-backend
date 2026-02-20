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
 *     path="/api/v1/chat/history/list",
 *     summary="Get chat history",
 *     description="Retrieve paginated chat message history with a specific user.",
 *     operationId="getChatHistory",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(name="receiver_id", in="query", required=true, @OA\Schema(type="integer", example=2)),
 *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", minimum=1, example=1)),
 *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", minimum=1, example=20)),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class GetAllChatHistoryAction {}
