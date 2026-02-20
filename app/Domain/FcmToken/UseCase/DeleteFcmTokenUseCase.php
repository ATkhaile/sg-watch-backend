<?php

namespace App\Domain\FcmToken\UseCase;

use App\Domain\FcmToken\Entity\DeleteFcmTokenRequestEntity;
use App\Domain\FcmToken\Entity\StatusEntity;
use App\Domain\FcmToken\Repository\FcmTokenRepository;
use App\Enums\StatusCode;

final class DeleteFcmTokenUseCase
{
    public function __construct(
        private FcmTokenRepository $fcmTokenRepository
    ) {
    }

    public function __invoke(DeleteFcmTokenRequestEntity $requestEntity): StatusEntity
    {
        if ($this->fcmTokenRepository->delete($requestEntity)) {
            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('fcm_token.delete.successful')
            );
        } else {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('fcm_token.delete.failed')
            );
        }
    }
}
