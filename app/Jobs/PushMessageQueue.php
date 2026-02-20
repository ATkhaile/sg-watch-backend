<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\MessageQueue;
use App\Models\MessageLog;
use App\Models\ChatMessage;

use App\Domain\Chat\UseCase\SendPusherMessageUseCase;
use App\Domain\Chat\UseCase\SendChatFirebaseNotificationUseCase;

class PushMessageQueue implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private int $messageQueueId,
    ) {}

    public function handle(
        SendPusherMessageUseCase $sendPusherMessageUseCase,
        SendChatFirebaseNotificationUseCase $sendChatFirebaseNotificationUseCase
    ): void {
        $logger = Log::channel('scenario_crontab');

        $messageQueue = MessageQueue::with([
            'media',
            'scenarioStep',
            'scenarioStep.createdBy',
        ])->find($this->messageQueueId);

        if (! $messageQueue) {
            $logger->warning("MessageQueue ID {$this->messageQueueId} not found");
            return;
        }

        DB::transaction(function () use (
            $messageQueue,
            $logger,
            $sendPusherMessageUseCase,
            $sendChatFirebaseNotificationUseCase,
        ) {
            $senderId = $messageQueue->scenarioStep?->created_by_id
                ?? config('chat.system_user_id', 1);

            $chatData = [
                'user_id' => (int) $senderId,
                'receiver_id' => (int) $messageQueue->user_id,
                'reply_to_message_id' => null,
                'is_read' => false,
            ];

            $chatData['message'] = $messageQueue->content ?? '';

            if ($messageQueue->media_id && $messageQueue->media) {
                $chatData['message_type'] = 'file';

                $chatData['file_url']  = $messageQueue->media->file_url ?? null;
                $chatData['file_name'] = $messageQueue->media->file_name ?? null;
                $fileName = $chatData['file_name'];

                $chatData['file_type'] = $fileName
                    ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION))
                    : null;

                $chatData['file_size'] = $messageQueue->media->file_size ?? null;

                if (empty($chatData['message'])) {
                    $chatData['message'] = $chatData['file_name']
                        ? ('Shared a file: ' . $chatData['file_name'])
                        : 'Shared a file';
                }
            } else {
                $chatData['message_type'] = 'text';
                $chatData['file_url'] = null;
                $chatData['file_name'] = null;
                $chatData['file_type'] = null;
                $chatData['file_size'] = null;
            }

            $chatMessage = ChatMessage::create($chatData);

            $logger->info("Scenario saved ChatMessage id={$chatMessage->id} from queue={$messageQueue->id}");

            if (env('CHAT_MODE', 'normal') === 'realtime') {
                $data = [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'user_id' => $chatMessage->user_id,
                    'receiver_id' => $chatMessage->receiver_id,
                    'message_type' => $chatMessage->message_type,
                    'created_at' => $chatMessage->created_at,

                    'file_url' => $chatMessage->file_url,
                    'file_name' => $chatMessage->file_name,
                    'file_type' => $chatMessage->file_type,
                    'file_size' => $chatMessage->file_size,

                    'chat_type' => 'direct',

                    'sender_type' => 'scenario',
                    'scenario_step_id' => $messageQueue->scenario_step_id,
                ];

                $sendPusherMessageUseCase->execute($data);

                $sendChatFirebaseNotificationUseCase->execute(
                    $data,
                    'direct',
                    [$chatMessage->receiver_id]
                );
            }

            $this->moveToMessageLog($messageQueue);
        });
    }

    private function moveToMessageLog(MessageQueue $messageQueue): void
    {
        MessageLog::create([
            'user_id' => $messageQueue->user_id,
            'sender_type' => $messageQueue->sender_type,
            'content' => $messageQueue->content,
            'media_id' => $messageQueue->media_id,
            'scenario_step_id' => $messageQueue->scenario_step_id,
            'sent_at' => now(),
        ]);

        $messageQueue->delete();
    }
}