<?php

namespace App\Domain\Chat\UseCase;

use App\Events\ChatMessageSent;
use Pusher\Pusher;
use App\Models\PusherInfo;
use App\Enums\PushType;

class SendPusherMessageUseCase
{
    public function execute(array $data, string $channel = 'chat-channel'): void
    {
        $setting = PusherInfo::where('push_type', PushType::PUSHER)->first();
        $options = [
            'cluster' => $setting ? $setting->pusher_app_cluster : config('broadcasting.connections.pusher.options.cluster'),
            'useTLS' => false
        ];
        $pusher = new Pusher(
            $setting ? $setting->pusher_app_key : config('broadcasting.connections.pusher.key'),
            $setting ? $setting->pusher_app_secret : config('broadcasting.connections.pusher.secret'),
            $setting ? $setting->pusher_app_id : config('broadcasting.connections.pusher.app_id'),
            $options
        );
        $pusher->trigger($channel, 'chat-event', $data);
    }
}
