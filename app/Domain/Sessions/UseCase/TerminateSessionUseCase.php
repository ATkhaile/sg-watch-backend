<?php

namespace App\Domain\Sessions\UseCase;

use App\Domain\Sessions\Entity\TerminateSessionRequestEntity;
use App\Domain\Sessions\Entity\StatusEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class TerminateSessionUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'terminate-sessions';

    public function __construct(
        private readonly SessionRepository $sessionRepository
    ) {
    }

    public function __invoke(TerminateSessionRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->sessionRepository->invalidateSession($requestEntity->getSessionId())) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('sessions.delete.success')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::INTERNAL_ERR,
            message: __('sessions.delete.failed')
        );
    }
}
