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
 *     path="/api/v1/chat/message",
 *     summary="Send a chat message",
 *     description="Send a message to another user. Either message text or file is required. Supports file attachments, mentions, and replies.",
 *     operationId="sendChatMessage",
 *     tags={"チャット - ユーザー間"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"receiver_id"},
 *                 @OA\Property(property="receiver_id", type="integer", description="ID of the message receiver", example=2),
 *                 @OA\Property(property="message", type="string", nullable=true, description="Message text (required if no file)", example="Hello there!"),
 *                 @OA\Property(property="file", type="string", format="binary", nullable=true, description="File attachment (optional)"),
 *                 @OA\Property(property="reply_to_message_id", type="integer", nullable=true, description="ID of message being replied to", example=123),
 *                 @OA\Property(
 *                     property="mentioned_user_ids",
 *                     type="array",
 *                     nullable=true,
 *                     description="Array of user IDs being mentioned",
 *                     @OA\Items(type="integer", example=3)
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */
class ChatMessageAction {}
