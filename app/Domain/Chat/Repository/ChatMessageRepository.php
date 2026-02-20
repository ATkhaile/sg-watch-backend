<?php

namespace App\Domain\Chat\Repository;

use App\Domain\Chat\Entity\ChatMessage;
use App\Domain\Chat\Entity\UsersEntity;
use App\Domain\Chat\Entity\ChatMessageListEntity;
use App\Domain\Chat\Entity\ConversationsListEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use App\Domain\Chat\Entity\AvailableUsersListEntity;

interface ChatMessageRepository
{
    public function create(array $data, ?UploadedFile $file = null): ChatMessage;
    public function getMessagesBetweenUsers(ChatMessageListEntity $chatMessageList): LengthAwarePaginator;
    public function markMessagesAsRead(int $userId, int $chatPartnerId): int;
    public function getUnreadMessagesCount(int $userId, int $chatPartnerId): int;
    public function getAvailableUsers(AvailableUsersListEntity $entity): LengthAwarePaginator;
    public function getConversations(ConversationsListEntity $entity): array;
    public function getAllUsers(UsersEntity $entity): LengthAwarePaginator;
}
