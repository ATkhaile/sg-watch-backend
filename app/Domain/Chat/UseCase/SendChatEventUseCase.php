<?php

namespace App\Domain\Chat\UseCase;

use Pusher\Pusher;
use Illuminate\Support\Facades\Log;
use App\Models\PusherInfo;
use App\Enums\PushType;

class SendChatEventUseCase
{
    private Pusher $pusher;

    public function __construct()
    {
        $setting = PusherInfo::where('push_type', PushType::PUSHER)->first();
        $this->pusher = new Pusher(
            $setting ? $setting->pusher_app_key : config('broadcasting.connections.pusher.key'),
            $setting ? $setting->pusher_app_secret : config('broadcasting.connections.pusher.secret'),
            $setting ? $setting->pusher_app_id : config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => $setting ? $setting->pusher_app_cluster : config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => false
            ]
        );
    }

    /**
     * Send chat event via Pusher
     *
     * @param array $data Event data
     * @param string $eventType Event type (e.g., 'new_message', 'message_read')
     */
    public function execute(array $data, string $eventType): void
    {
        try {
            if (isset($data['channel'])) {
                $this->pusher->trigger($data['channel'], $eventType, $data);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send chat event: ' . $e->getMessage());
        }
    }
}
