<?php

namespace App\Docs\Actions\Api\Chat;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="チャット - ユーザー間",
 *     description="Chat - User to User"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/chat/typing/stop",
 *     summary="Stop typing indicator",
 *     description="Notify that the user has stopped typing a message.",
 *     operationId="stopTyping",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class StopTypingAction {}
