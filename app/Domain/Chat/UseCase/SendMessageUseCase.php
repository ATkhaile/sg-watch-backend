<?php

namespace App\Domain\Chat\UseCase;

use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Domain\Chat\Entity\ChatMessage;
use App\Http\Requests\Api\Chat\SendMessageRequest;

class SendMessageUseCase
{
    public function __construct(
        private ChatMessageRepository $repository
    ) {
    }

    public function execute(array $data, SendMessageRequest $request): ChatMessage
    {
        $file = $request->hasFile('file') ? $request->file('file') : null;
        return $this->repository->create($data, $file);
    }
}
