<?php

namespace App\Domain\Firebase\UseCase;

use App\Domain\Firebase\Repository\FirebaseRepository;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetFirebaseNotificationDetailUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-firebase-notifications';

    public function __construct(
        private FirebaseRepository $firebaseRepository
    ) {
    }

    public function __invoke(int $notificationId, string $fcmToken): ?array
    {
        $this->authorize();

        return $this->firebaseRepository->getNotificationDetail($notificationId, $fcmToken);
    }
}
