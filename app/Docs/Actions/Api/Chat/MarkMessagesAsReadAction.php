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
 *     path="/api/v1/chat/messages/mark-as-read",
 *     summary="Mark messages as read",
 *     description="Mark chat messages as read for a specific conversation.",
 *     operationId="markMessagesAsRead",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized")
 * )
 */
class MarkMessagesAsReadAction {}
