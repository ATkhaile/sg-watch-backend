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
 *     path="/api/v1/chat/room/leave",
 *     summary="Leave chat room",
 *     description="Leave a chat room.",
 *     operationId="leaveChatRoom",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class LeaveChatRoomAction {}
