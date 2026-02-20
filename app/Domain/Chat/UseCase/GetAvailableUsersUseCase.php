<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Entity\AvailableUsersListEntity;
use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Components\CommonComponent;
use App\Enums\StatusCode;

class GetAvailableUsersUseCase
{
    public function __construct(
        private ChatMessageRepository $chatMessageRepository
    ) {
    }

    public function execute(AvailableUsersListEntity $entity): AvailableUsersListEntity
    {
        $data = $this->chatMessageRepository->getAvailableUsers($entity);
        $currentUserId = $entity->getUserId();

        $users = collect($data->items())->map(function ($user) use ($currentUserId) {
            $latestMessage = \App\Models\ChatMessage::where(function($query) use ($currentUserId, $user) {
                    $query->where('user_id', $currentUserId)->where('receiver_id', $user->id);
                })
                ->orWhere(function($query) use ($currentUserId, $user) {
                    $query->where('user_id', $user->id)->where('receiver_id', $currentUserId);
                })
                ->orderBy('created_at', 'desc')
                ->with('user')
                ->first();

            $unreadCount = \App\Models\ChatMessage::where('user_id', $user->id)
                ->where('receiver_id', $currentUserId)
                ->where('is_read', false)
                ->count();

            $avatarUrl = $user->image ? CommonComponent::getFullUrl($user->image) : null;

            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'image' => $avatarUrl,
                'unread_count' => $unreadCount,
                'latest_message' => $latestMessage ? [
                    'id' => $latestMessage->id,
                    'message' => $latestMessage->message,
                    'message_type' => $latestMessage->message_type ?? 'text',
                    'user_name' => $latestMessage->user ? $latestMessage->user->full_name : 'Unknown',
                    'created_at' => $latestMessage->created_at,
                    'file_url' => $latestMessage->file_url,
                    'file_name' => $latestMessage->file_name,
                    'file_type' => $latestMessage->file_type,
                    'file_size' => $latestMessage->file_size,
                ] : null,
            ];
        })->toArray();

        $entity->setUsers($users);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
