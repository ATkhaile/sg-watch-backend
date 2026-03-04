<?php

namespace App\Domain\Chat\UseCase;

use Pusher\Pusher;
use App\Models\PusherInfo;
use App\Enums\PushType;

class SendPusherMessageUseCase
{
    public function execute(array $data, string $channel = 'chat-channel'): void
    {
        $setting = PusherInfo::where('push_type', PushType::PUSHER)->first();

        $appKey = $setting ? $setting->pusher_app_key : config('broadcasting.connections.pusher.key');
        $appSecret = $setting ? $setting->pusher_app_secret : config('broadcasting.connections.pusher.secret');
        $appId = $setting ? $setting->pusher_app_id : config('broadcasting.connections.pusher.app_id');
        $cluster = $setting ? $setting->pusher_app_cluster : config('broadcasting.connections.pusher.options.cluster');

        $pusher = new Pusher($appKey, $appSecret, $appId, [
            'cluster' => $cluster,
            'useTLS' => false,
        ]);

        $pusher->trigger($channel, 'chat-event', $data);
    }
}
