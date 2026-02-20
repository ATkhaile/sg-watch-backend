<?php

namespace App\Domain\Firebase\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Firebase\Entity\UpdateFirebaseNotificationReadedEntity;
use App\Domain\Firebase\Entity\StatusEntity;
use App\Domain\Firebase\Repository\FirebaseRepository;
use App\Enums\StatusCode;

final class UpdateFirebaseNotificationReadedUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-firebase-notification-readed';

    public function __construct(
        private FirebaseRepository $firebaseRepository
    ) {
    }

    public function __invoke(UpdateFirebaseNotificationReadedEntity $entity, int $id): StatusEntity
    {
        $this->authorize();

        if (!$this->firebaseRepository->updateNotificationReaded($entity, $id)) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('firebase.update_readed.failed')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('firebase.update_readed.success')
            );
        }
    }
}
