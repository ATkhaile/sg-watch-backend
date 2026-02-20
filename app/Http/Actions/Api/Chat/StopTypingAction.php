<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\SendChatTypingEventUseCase;
use App\Http\Requests\Api\Chat\ChatTypingRequest;
use App\Http\Resources\Api\Chat\ActionResource;
use App\Http\Responders\Api\Chat\ActionResponder as ChatActionResponder;

class StopTypingAction
{
    public function __construct(
        private SendChatTypingEventUseCase $sendChatTypingEventUseCase,
        private ChatActionResponder $responder
    ) {}

    public function __invoke(ChatTypingRequest $request): ActionResource
    {
        $user = auth()->user();
        $receiverId = $request->input('receiver_id');

        $params = [
            'user_id' => $user->id,
            'user_name' => $user->full_name,
            'receiver_id' => $receiverId,
            'event_type' => 'user_typing_stop',
        ];

        $this->sendChatTypingEventUseCase->execute($params);

        return $this->responder->__invoke([
            'status_code' => 200,
            'message' => __('chat.typing.stopped'),
            'data' => [
                'user_id' => $params['user_id'],
                'receiver_id' => $params['receiver_id'],
                'event_type' => $params['event_type'],
            ]
        ]);
    }
}
