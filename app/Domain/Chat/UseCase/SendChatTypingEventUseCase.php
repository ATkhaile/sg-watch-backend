<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Infrastructure\DbChatTypingInfrastructure;
use Pusher\Pusher;

class SendChatTypingEventUseCase
{
    public function __construct(
        private DbChatTypingInfrastructure $dbChatTypingInfrastructure
    ) {}

    public function execute(array $data): void
    {
        $mode = env('CHAT_MODE', 'normal');

        if ($mode !== 'realtime') {
            return;
        }

        // Validate Pusher configuration
        $pusherKey = env('PUSHER_APP_KEY');
        $pusherSecret = env('PUSHER_APP_SECRET');
        $pusherAppId = env('PUSHER_APP_ID');
        $pusherCluster = env('PUSHER_APP_CLUSTER');

        if (!$pusherKey || !$pusherSecret || !$pusherAppId || !$pusherCluster) {
            \Log::warning('Pusher configuration is incomplete', [
                'has_key' => !empty($pusherKey),
                'has_secret' => !empty($pusherSecret), 
                'has_app_id' => !empty($pusherAppId),
                'has_cluster' => !empty($pusherCluster),
            ]);
            return;
        }

        // Create typing event data
        $typingData = $this->dbChatTypingInfrastructure->prepareTypingEventData($data);

        $pusher = new Pusher(
            $pusherKey,
            $pusherSecret,
            $pusherAppId,
            [
                'cluster' => $pusherCluster,
                'useTLS' => true
            ]
        );

        $eventName = $data['event_type'];
        $roomName = $typingData['room_name'];

        $eventPayload = [
            'user_id' => $data['user_id'],
            'user_name' => $data['user_name'] ?? null,
            'event_type' => $eventName,
            'timestamp' => $typingData['timestamp'],
            'room_name' => $roomName,
            'receiver_id' => $data['receiver_id'],
        ];

        $pusher->trigger($roomName, $eventName, $eventPayload);
    }
}