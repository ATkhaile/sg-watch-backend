<?php

namespace App\Domain\Google\UseCase;

use App\Domain\Google\Entity\GoogleAppLoginRequestEntity;
use App\Domain\Google\Entity\GoogleAppLoginResponseEntity;
use App\Domain\Google\Repository\GoogleRepository;
use App\Enums\StatusCode;

final class GoogleAppLoginUseCase
{
    public function __construct(
        private GoogleRepository $googleRepository
    ) {
    }

    public function __invoke(GoogleAppLoginRequestEntity $requestEntity): GoogleAppLoginResponseEntity
    {
        try {
            $token = $this->googleRepository->appLogin($requestEntity);

            return new GoogleAppLoginResponseEntity(
                token: $token,
                statusCode: StatusCode::OK,
                message: __('google.callback.success')
            );
        } catch (\Throwable $e) {
            return new GoogleAppLoginResponseEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: $e->getMessage()
            );
        }
    }
}