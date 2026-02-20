<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\MarkMessagesAsReadUseCase;
use App\Domain\Chat\UseCase\SendPusherMessageUseCase;
use App\Http\Requests\Api\Chat\MarkMessagesAsReadRequest;
use App\Http\Resources\Api\Chat\ActionResource;
use App\Http\Responders\Api\Chat\ActionResponder as ChatActionResponder;

class MarkMessagesAsReadAction
{
    public function __construct(
        private ChatActionResponder $responder,
        private MarkMessagesAsReadUseCase $markMessagesAsReadUseCase,
        private SendPusherMessageUseCase $sendPusherMessageUseCase
    ) {
    }

    public function __invoke(MarkMessagesAsReadRequest $request): ActionResource
    {
        $user = auth()->user();
        $chatPartnerId = $request->validated('chat_partner_id');

        $markedCount = $this->markMessagesAsReadUseCase->execute($user, $chatPartnerId);

        // Send Pusher event to notify the sender that messages were read
        // Always send event even if markedCount is 0, so the partner knows this user opened the chat
        $this->sendPusherMessageUseCase->execute([
            'event_type' => 'messages_read',
            'reader_id' => $user->id,
            'sender_id' => $chatPartnerId,
            'marked_count' => $markedCount,
            'chat_type' => 'direct',
        ], 'chat-channel');

        return ($this->responder)([
            'status_code' => 200,
            'message' => __('chat.messages_marked_as_read_successfully'),
            'data' => [
                'marked_count' => $markedCount,
                'chat_partner_id' => $chatPartnerId
            ]
        ]);
    }
}