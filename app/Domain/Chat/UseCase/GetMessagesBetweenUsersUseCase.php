<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Entity\ChatMessageListEntity;
use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Components\CommonComponent;
use App\Enums\StatusCode;

final class GetMessagesBetweenUsersUseCase
{
    public function __construct(
        private ChatMessageRepository $chatMessageRepository
    ) {}

    public function __invoke(ChatMessageListEntity $entity): ChatMessageListEntity
    {
        $data = $this->chatMessageRepository->getMessagesBetweenUsers($entity);
        $userId = $entity->getUserId();
        $receiverId = $entity->getReceiverId();

        $messages = collect($data->items())->map(function ($msg) {
            // Get full avatar URLs
            $senderAvatar = $msg->sender_avatar ? CommonComponent::getFullUrl($msg->sender_avatar) : null;
            $receiverAvatar = $msg->receiver_avatar ? CommonComponent::getFullUrl($msg->receiver_avatar) : null;

            $messageData = [
                'id' => $msg->id,
                'user_id' => $msg->user_id,
                'sender_name' => $msg->sender_name,
                'sender_avatar' => $senderAvatar,
                'user_name' => $msg->sender_name,
                'user_avatar' => $senderAvatar,
                'receiver_id' => $msg->receiver_id,
                'receiver_name' => $msg->receiver_name,
                'receiver_avatar' => $receiverAvatar,
                'reply_to_message_id' => $msg->reply_to_message_id,
                'message' => $msg->message,
                'message_type' => $msg->message_type,
                'file_url' => $msg->file_url,
                'file_name' => $msg->file_name,
                'file_type' => $msg->file_type,
                'file_size' => $msg->file_size,
                'is_read' => (bool) $msg->is_read,
                'read_at' => $msg->read_at,
                'created_at' => $msg->created_at,
                'updated_at' => $msg->updated_at,
            ];

            if ($msg->replyToMessage) {
                $messageData['reply_to_message'] = [
                    'id' => $msg->replyToMessage->id,
                    'user_id' => $msg->replyToMessage->user_id,
                    'user_name' => $msg->replyToMessage->user->full_name ?? 'Unknown',
                    'message' => $msg->replyToMessage->message,
                    'message_type' => $msg->replyToMessage->message_type,
                    'created_at' => $msg->replyToMessage->created_at,
                ];
            } else {
                $messageData['reply_to_message'] = null;
            }

            if ($msg->mentions && $msg->mentions->count() > 0) {
                $messageData['mentions'] = $msg->mentions->map(function ($mention) {
                    return [
                        'user_id' => $mention->mentioned_user_id,
                        'user_name' => $mention->mentionedUser->full_name ?? 'Unknown',
                    ];
                })->toArray();
            } else {
                $messageData['mentions'] = [];
            }

            return $messageData;
        })->toArray();

        // Get unread messages count from the other user to current user
        $unreadCount = $this->chatMessageRepository->getUnreadMessagesCount($userId, $receiverId);

        // Mark messages as read when viewing chat history
        $this->chatMessageRepository->markMessagesAsRead($userId, $receiverId);

        $entity->setMessages($messages);
        $entity->setUnreadCount($unreadCount);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
