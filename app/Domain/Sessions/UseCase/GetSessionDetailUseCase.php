<?php

namespace App\Domain\Sessions\UseCase;

use App\Domain\Sessions\Entity\GetSessionDetailRequestEntity;
use App\Domain\Sessions\Entity\SessionDetailEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use App\Exceptions\Domain\NotFoundResourceException;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetSessionDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'find-sessions';

    public function __construct(
        private readonly SessionRepository $sessionRepository
    ) {
    }

    public function __invoke(GetSessionDetailRequestEntity $requestEntity): SessionDetailEntity
    {
        $this->authorize();

        $session = $this->sessionRepository->getSessionById($requestEntity->getSessionId());

        if (!$session) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return $session;
    }
}
