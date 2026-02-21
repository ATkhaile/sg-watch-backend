<?php

namespace App\Domain\Sessions\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;
use App\Domain\Sessions\Entity\GetAllSessionsEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllSessionsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-sessions';

    public function __construct(
        private SessionRepository $sessionRepository
    ) {
    }

    public function __invoke(GetAllSessionsEntity $entity): GetAllSessionsEntity
    {
        $this->authorize();

        $data = $this->sessionRepository->getAllSessions($entity);

        $sessions = collect($data->items())->map(function ($session) {
            return [
                'id' => $session->id,
                'user_id' => $session->user_id,
                'ip_address' => $session->ip_address,
                'user_agent' => $session->user_agent,
                'last_activity' => $session->last_activity,
                'is_active' => $session->is_active
            ];
        })->toArray();

        $entity->setData($sessions);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatusCode(StatusCode::OK);

        return $entity;
    }
}
