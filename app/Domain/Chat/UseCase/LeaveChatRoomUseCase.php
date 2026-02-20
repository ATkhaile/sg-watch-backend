<?php

namespace App\Domain\Chat\UseCase;

use App\Models\User;

class LeaveChatRoomUseCase
{
    public function execute(array $params): array
    {
        $userId = $params['user_id'];
        $receiverId = $params['receiver_id'];

        $user = User::find($userId);
        $receiver = User::find($receiverId);

        if (!$user) {
            throw new \Exception('User not found');
        }
        if (!$receiver) {
            throw new \Exception('Receiver not found');
        }

        $roomName = $this->generatePrivateRoomName($userId, $receiverId);

        return [
            'user_id' => $userId,
            'receiver_id' => $receiverId,
            'room_name' => $roomName,
            'room_display_name' => $this->generateRoomDisplayName($user, $receiver),
            'participants' => [$userId, $receiverId],
            'event_type' => 'user_left_chat',
            'timestamp' => now()->toISOString(),
        ];
    }

    private function generatePrivateRoomName(int $userId1, int $userId2): string
    {
        $minId = min($userId1, $userId2);
        $maxId = max($userId1, $userId2);
        return "private_chat_{$minId}_{$maxId}";
    }

    private function generateRoomDisplayName(User $user, User $receiver): string
    {
        return "Chat between {$user->full_name} and {$receiver->full_name}";
    }
}
