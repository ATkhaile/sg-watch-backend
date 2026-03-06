<?php

namespace App\Domain\FcmToken\UseCase;

use App\Domain\FcmToken\Entity\CreateFcmTokenRequestEntity;
use App\Domain\FcmToken\Entity\StatusEntity;
use App\Domain\FcmToken\Repository\FcmTokenRepository;
use App\Enums\StatusCode;

final class CreateFcmTokenUseCase
{
    public function __construct(
        private FcmTokenRepository $fcmTokenRepository
    ) {
    }

    public function __invoke(CreateFcmTokenRequestEntity $requestEntity): StatusEntity
    {

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
