<?php

namespace App\Domain\Users\UseCase;

use App\Components\CommonComponent;
use App\Domain\Shared\Concerns\RequiresPermission;
use App\Domain\Users\Entity\UserSessionDevicesEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Enums\StatusCode;

final class GetUserSessionDevicesUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-sessions';

    public function __construct(
        private UsersRepository $usersRepository
    ) {}

    public function __invoke(UserSessionDevicesEntity $entity): UserSessionDevicesEntity
    {
        $this->authorize();

        $data = $this->usersRepository->getUserSessionDevices($entity);

        $entity->sessions = collect($data->items())->map(function ($s) {
            return [
                'id' => $s->id,
                'ip_address' => $s->ip_address,
                'user_agent' => $s->user_agent,
                'app_id' => $s->app_id ?? null,
                'domain' => $s->domain ?? null,
                'last_activity' => $s->last_activity,
                'is_active' => (bool) $s->is_active,
            ];
        })->toArray();

        $entity->pagination = CommonComponent::getPaginationData($data);
        $entity->statusCode = StatusCode::OK;
        return $entity;
    }
}
