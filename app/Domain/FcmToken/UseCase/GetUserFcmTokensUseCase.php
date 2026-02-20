<?php

namespace App\Domain\FcmToken\UseCase;

use App\Domain\FcmToken\Entity\FcmTokenListEntity;
use App\Domain\FcmToken\Repository\FcmTokenRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetUserFcmTokensUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-fcm-tokens';
    public function __construct(private FcmTokenRepository $repository) {}

    public function __invoke(int $userId): FcmTokenListEntity
    {
        $this->authorize();

        $tokens = $this->repository->getByUserId($userId);

        return new FcmTokenListEntity(
            fcmTokens: $tokens,
            statusCode: StatusCode::OK,
        );
    }
}
