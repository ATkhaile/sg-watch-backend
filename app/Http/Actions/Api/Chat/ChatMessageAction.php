<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\SendMessageUseCase;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Domain\Chat\Factory\SendMessageRequestFactory;
use App\Http\Resources\Api\Chat\ActionResource;
use App\Http\Responders\Api\Chat\ActionResponder as ChatActionResponder;
use App\Domain\Chat\UseCase\SendPusherMessageUseCase;
use App\Domain\Chat\UseCase\SendChatFirebaseNotificationUseCase;

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
        $mode = env('CHAT_MODE', 'normal');

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
            ? \App\Components\CommonComponent::getFullUrl($messageWithRelations->user->avatar_url)
            : '';
        $receiverName = $messageWithRelations?->receiver?->full_name ?? 'Unknown';
        $receiverAvatar = $messageWithRelations?->receiver?->avatar_url
            ? \App\Components\CommonComponent::getFullUrl($messageWithRelations->receiver->avatar_url)
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

        $responseData = [
            'id' => $chatMessage->getId(),
            'message' => $chatMessage->getMessage(),
            'user_id' => $chatMessage->getUserId(),
            'sender_name' => $senderName,
            'sender_avatar' => $senderAvatar,
            'receiver_id' => $chatMessage->getReceiverId(),
            'receiver_name' => $receiverName,
            'receiver_avatar' => $receiverAvatar,
            'message_type' => $chatMessage->getMessageType(),
            'created_at' => $chatMessage->getCreatedAt(),
            'file_url' => $chatMessage->getFileUrl(),
            'file_name' => $chatMessage->getFileName(),
            'file_type' => $chatMessage->getFileType(),
            'file_size' => $chatMessage->getFileSize(),
            'chat_type' => 'direct',
            'reply_to_message_id' => $messageWithRelations ? $messageWithRelations->reply_to_message_id : null,
            'reply_to_message' => $replyData,
        ];

        // Always send Pusher event for realtime updates (regardless of mode)
        $this->sendPusherMessageUseCase->execute($responseData);

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

    private function createErrorResponse(): ActionResource
    {
        return ($this->responder)([
            'status_code' => 400,
            'message' => __('chat.invalid.success'),
            'data' => null
        ]);
    }
}
