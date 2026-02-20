<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Entity\ChatPartnerEntity;
use App\Components\CommonComponent;
use App\Enums\StatusCode;
use App\Models\User;
use App\Models\ChatMessage;

class GetChatPartnerUseCase
{
    public function execute(ChatPartnerEntity $entity): ChatPartnerEntity
    {
        $currentUserId = $entity->getUserId();
        $partnerId = $entity->getPartnerId();

        $user = User::find($partnerId);

        if (!$user) {
            $entity->setStatus(StatusCode::NOT_FOUND);
            return $entity;
        }

        $latestMessage = ChatMessage::where(function($query) use ($currentUserId, $partnerId) {
                $query->where('user_id', $currentUserId)->where('receiver_id', $partnerId);
            })
            ->orWhere(function($query) use ($currentUserId, $partnerId) {
                $query->where('user_id', $partnerId)->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->first();

        $unreadCount = ChatMessage::where('user_id', $partnerId)
            ->where('receiver_id', $currentUserId)
            ->where('is_read', false)
            ->count();

        $avatarUrl = CommonComponent::getFullUrl($user->avatar_url);

        $partner = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'avatar_url' => $avatarUrl,
            'unread_count' => $unreadCount,
            'latest_message' => $latestMessage ? [
                'id' => $latestMessage->id,
                'message' => $latestMessage->message,
                'message_type' => $latestMessage->message_type ?? 'text',
                'user_id' => $latestMessage->user_id,
                'user_name' => $latestMessage->user ? $latestMessage->user->full_name : 'Unknown',
                'created_at' => $latestMessage->created_at,
                'file_url' => $latestMessage->file_url,
                'file_name' => $latestMessage->file_name,
                'file_type' => $latestMessage->file_type,
                'file_size' => $latestMessage->file_size,
            ] : null,
        ];

        $entity->setPartner($partner);
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
