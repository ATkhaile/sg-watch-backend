<?php

namespace App\Domain\Sessions\UseCase;

use App\Domain\Sessions\Repository\SessionRepository;
use App\Domain\Sessions\Entity\StatusEntity;
use App\Domain\Sessions\Entity\CreateSessionRequestEntity;
use App\Enums\StatusCode;

final class CreateSessionUseCase
{
    public function __construct(
        private SessionRepository $sessionRepository
    ) {
    }

    public function __invoke(CreateSessionRequestEntity $requestEntity): StatusEntity
    {
        if ($this->sessionRepository->store($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('sessions.create.success')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('sessions.create.failed')
            );
        }
    }
}
