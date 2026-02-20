<?php

namespace App\Domain\Google\UseCase;

use App\Domain\Google\Entity\GoogleCallbackRequestEntity;
use App\Domain\Google\Entity\GoogleCallbackResponseEntity;
use App\Domain\Google\Repository\GoogleRepository;
use App\Enums\StatusCode;

final class GoogleCallbackUseCase
{
    public function __construct(
        private GoogleRepository $googleRepository
    ) {}

    public function __invoke(GoogleCallbackRequestEntity $requestEntity): GoogleCallbackResponseEntity
    {
        try {
            $res = $this->googleRepository->login($requestEntity);

            return new GoogleCallbackResponseEntity(
                token: $res['token'],
                isFirstLogin: $res['is_first_login'],
                statusCode: StatusCode::OK,
                message: __('google.callback.success')
            );
        } catch (\Throwable $e) {
            return new GoogleCallbackResponseEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: $e->getMessage()
            );
        }
    }
}
