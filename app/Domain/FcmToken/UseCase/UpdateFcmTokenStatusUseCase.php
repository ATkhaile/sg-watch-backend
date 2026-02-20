<?php

namespace App\Domain\FcmToken\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\FcmToken\Entity\StatusEntity;
use App\Domain\FcmToken\Entity\UpdateFcmTokenStatusRequestEntity;
use App\Domain\FcmToken\Repository\FcmTokenRepository;
use App\Enums\StatusCode;

final class UpdateFcmTokenStatusUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'update-fcm-token-status';

    public function __construct(private FcmTokenRepository $repository) {}

    public function __invoke(UpdateFcmTokenStatusRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        if (!$this->repository->updateStatus($entity)) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('fcm_token.update_status.failed')
            );
        }

        return new StatusEntity(
            statusCode: StatusCode::OK,
            message: __('fcm_token.update_status.success')
        );
    }
}
