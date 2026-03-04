<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\MarkMessagesAsReadUseCase;
use App\Http\Requests\Api\Chat\MarkMessagesAsReadRequest;
use App\Http\Resources\Api\Chat\ActionResource;
use App\Http\Responders\Api\Chat\ActionResponder as ChatActionResponder;

class MarkMessagesAsReadAction
{
    public function __construct(
        private ChatActionResponder $responder,
        private MarkMessagesAsReadUseCase $markMessagesAsReadUseCase
    ) {
    }

    public function __invoke(MarkMessagesAsReadRequest $request): ActionResource
    {
        $user = auth()->user();
        $chatPartnerId = $request->validated('chat_partner_id');

        $markedCount = $this->markMessagesAsReadUseCase->execute($user, $chatPartnerId);

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