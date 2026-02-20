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
 *     path="/api/v1/chat/room/join",
 *     summary="Join chat room",
 *     description="Join a chat room for real-time messaging with another user.",
 *     operationId="joinChatRoom",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "receiver_id"},
 *             @OA\Property(property="user_id", type="integer", description="Current user ID", example=1),
 *             @OA\Property(property="receiver_id", type="integer", description="Receiver user ID", example=2)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class JoinChatRoomAction {}
