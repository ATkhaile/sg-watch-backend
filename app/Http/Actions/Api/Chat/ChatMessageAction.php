<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\SendMessageUseCase;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Domain\Chat\Factory\SendMessageRequestFactory;
use App\Http\Resources\Api\Chat\ActionResource;
use App\Http\Responders\Api\Chat\ActionResponder as ChatActionResponder;
use App\Domain\Chat\UseCase\SendPusherMessageUseCase;
use App\Domain\Chat\UseCase\SendChatFirebaseNotificationUseCase;
use App\Components\CommonComponent;

class ChatMessageAction
{
    public function __construct(
        private SendMessageUseCase $sendMessageUseCase,
        private SendMessageRequestFactory $factory,
        private ChatActionResponder $responder,
        private SendPusherMessageUseCase $sendPusherMessageUseCase,
        private SendChatFirebaseNotificationUseCase $sendChatFirebaseNotificationUseCase
    ) {}

    public function __invoke(SendMessageRequest $request): ActionResource
    {
        $params = $this->factory->createFromRequest($request);

        $chatMessage = $this->sendMessageUseCase->execute($params, $request);

        // Load the full message with reply data and user relationships for the response
        $messageWithRelations = \App\Models\ChatMessage::with([
            'user:id,first_name,last_name,avatar_url',
            'receiver:id,first_name,last_name,avatar_url',
            'replyToMessage:id,user_id,message,message_type,created_at',
            'replyToMessage.user:id,first_name,last_name'
        ])->find($chatMessage->getId());

        // Get sender and receiver names and avatars
        $senderName = $messageWithRelations?->user?->full_name ?? 'Unknown';
        $senderAvatar = $messageWithRelations?->user?->avatar_url
            ? CommonComponent::getFullUrl($messageWithRelations->user->avatar_url)
            : '';
        $receiverName = $messageWithRelations?->receiver?->full_name ?? 'Unknown';
        $receiverAvatar = $messageWithRelations?->receiver?->avatar_url
            ? CommonComponent::getFullUrl($messageWithRelations->receiver->avatar_url)
            : '';

        $replyData = null;
        if ($messageWithRelations && $messageWithRelations->replyToMessage) {
            $replyData = [
                'id' => $messageWithRelations->replyToMessage->id,
                'user_id' => $messageWithRelations->replyToMessage->user_id,
                'user_name' => $messageWithRelations->replyToMessage->user->full_name ?? 'Unknown',
                'message' => $messageWithRelations->replyToMessage->message,
                'message_type' => $messageWithRelations->replyToMessage->message_type,
                'created_at' => $messageWithRelations->replyToMessage->created_at,
            ];
        }

        $fileUrl = $messageWithRelations?->file_url
            ? CommonComponent::getFullUrl($messageWithRelations->file_url)
            : null;

        $responseData = [
            'id' => $messageWithRelations->id,
            'user_id' => $messageWithRelations->user_id,
            'sender_name' => $senderName,
            'sender_avatar' => $senderAvatar,
            'user_name' => $senderName,
            'user_avatar' => $senderAvatar,
            'receiver_id' => $messageWithRelations->receiver_id,
            'receiver_name' => $receiverName,
            'receiver_avatar' => $receiverAvatar,
            'reply_to_message_id' => $messageWithRelations->reply_to_message_id,
            'message' => $messageWithRelations->message,
            'message_type' => $messageWithRelations->message_type,
            'file_url' => $fileUrl,
            'file_name' => $messageWithRelations->file_name,
            'file_type' => $messageWithRelations->file_type,
            'file_size' => $messageWithRelations->file_size,
            'is_read' => false,
            'read_at' => null,
            'created_at' => $messageWithRelations->created_at,
            'updated_at' => $messageWithRelations->updated_at,
            'chat_type' => 'direct',
            'reply_to_message' => $replyData,
            'mentions' => [],
        ];

        // Send Pusher event for realtime updates
        $this->sendPusherMessageUseCase->execute($responseData);

        // Send Firebase push notification
        $this->sendChatFirebaseNotificationUseCase->execute(
            $responseData,
            'direct',
            [$chatMessage->getReceiverId()]
        );

        return $this->createSuccessResponse($responseData);
    }

    private function createSuccessResponse(array $data): ActionResource
    {
        return ($this->responder)([
            'status_code' => 200,
            'message' => __('chat.create.success'),
            'data' => $data
        ]);
    }
}
