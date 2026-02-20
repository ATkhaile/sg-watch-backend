<?php

namespace App\Domain\Chat\Infrastructure;

class DbChatTypingInfrastructure
{
    public function prepareTypingEventData(array $data): array
    {
        $userId = $data['user_id'];
        $receiverId = $data['receiver_id'] ?? null;

        $roomName = $this->generateRoomName($userId, $receiverId);

        return [
            'user_id' => $userId,
            'receiver_id' => $receiverId,
            'room_name' => $roomName,
            'event_type' => $data['event_type'],
            'timestamp' => now()->toISOString(),
        ];
    }

    private function generateRoomName(int $userId, ?int $receiverId): string
    {
        if ($receiverId) {
            $roomParticipants = [$userId, $receiverId];
            sort($roomParticipants);
            return 'chat-room-' . implode('-', $roomParticipants);
        }

        return 'chat-room-' . $userId;
    }
}
