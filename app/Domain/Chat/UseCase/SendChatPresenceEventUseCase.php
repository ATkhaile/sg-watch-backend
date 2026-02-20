<?php

namespace App\Domain\Chat\UseCase;

use Pusher\Pusher;
use App\Models\PusherInfo;
use App\Enums\PushType;

class SendChatPresenceEventUseCase
{
    public function execute(array $data): void
    {
        $mode = env('CHAT_MODE', 'normal');

        if ($mode !== 'realtime') {
            return;
        }

        $setting = PusherInfo::where('push_type', PushType::PUSHER)->first();
        $pusher = new Pusher(
            $setting ? $setting->pusher_app_key : config('broadcasting.connections.pusher.key'),
            $setting ? $setting->pusher_app_secret : config('broadcasting.connections.pusher.secret'),
            $setting ? $setting->pusher_app_id : config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => $setting ? $setting->pusher_app_cluster : config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true
            ]
        );

        $eventName = $data['event_type'];
        $roomName = $data['room_name'];

        $pusher->trigger($roomName, $eventName, [
            'user_id' => $data['user_id'],
            'timestamp' => $data['timestamp'],
            'room_display_name' => $data['room_display_name'] ?? $roomName,
            'receiver_id' => $data['receiver_id'] ?? null,
        ]);
    }
}
