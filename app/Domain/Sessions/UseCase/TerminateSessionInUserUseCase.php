<?php

namespace App\Domain\Sessions\UseCase;

use App\Domain\Sessions\Entity\TerminateSessionInUserRequestEntity;
use App\Domain\Sessions\Entity\StatusEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class TerminateSessionInUserUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'terminate-sessions';

    public function __construct(
        private readonly SessionRepository $sessionRepository
    ) {
    }

    public function __invoke(TerminateSessionInUserRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->sessionRepository->invalidateSessionInUser($requestEntity->getSessionId())) {
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
