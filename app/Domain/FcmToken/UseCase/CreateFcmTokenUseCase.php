<?php

namespace App\Domain\FcmToken\UseCase;

use App\Domain\FcmToken\Entity\CreateFcmTokenRequestEntity;
use App\Domain\FcmToken\Entity\StatusEntity;
use App\Domain\FcmToken\Repository\FcmTokenRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class CreateFcmTokenUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-fcm-tokens';
    public function __construct(
        private FcmTokenRepository $fcmTokenRepository
    ) {
    }

    public function __invoke(CreateFcmTokenRequestEntity $requestEntity): StatusEntity
    {
        $this->authorize();

        if ($this->fcmTokenRepository->store($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('fcm_token.create.successful')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('fcm_token.create.failed')
            );
        }
    }
}
