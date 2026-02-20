<?php

namespace App\Http\Actions\Api\Chat;

use App\Domain\Chat\UseCase\JoinChatRoomUseCase;
use App\Domain\Chat\UseCase\SendChatPresenceEventUseCase;
use App\Http\Requests\Api\Chat\JoinChatRoomRequest;
use App\Http\Resources\Api\Chat\ActionResource;
use App\Http\Responders\Api\Chat\ActionResponder as ChatActionResponder;

class JoinChatRoomAction
{
    public function __construct(
        private JoinChatRoomUseCase $joinChatRoomUseCase,
        private SendChatPresenceEventUseCase $sendChatPresenceEventUseCase,
        private ChatActionResponder $responder
    ) {}

    public function __invoke(JoinChatRoomRequest $request): ActionResource
    {
        $params = [
            'user_id' => $request->input('user_id'),
            'receiver_id' => $request->input('receiver_id'),
        ];

        $presenceData = $this->joinChatRoomUseCase->execute($params);
        $this->sendChatPresenceEventUseCase->execute($presenceData);

        return $this->responder->__invoke([
            'status_code' => 200,
            'message' => __('chat.room.joined'),
            'data' => [
                'room_name' => $presenceData['room_name'],
                'user_id' => $presenceData['user_id'],
                'receiver_id' => $presenceData['receiver_id'],
            ]
        ]);
    }
}
