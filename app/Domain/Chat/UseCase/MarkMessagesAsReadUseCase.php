<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Models\User;

class MarkMessagesAsReadUseCase
{
    public function __construct(
        private ChatMessageRepository $repository
    ) {
    }

    public function execute(User $user, int $chatPartnerId): int
    {
        return $this->repository->markMessagesAsRead($user->id, $chatPartnerId);
    }
}