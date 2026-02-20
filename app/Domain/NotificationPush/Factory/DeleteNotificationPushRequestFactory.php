<?php 
namespace App\Domain\NotificationPush\Factory;

use App\Domain\NotificationPush\Entity\DeleteNotificationPushRequestEntity;
use App\Http\Requests\Api\NotificationPush\DeleteNotificationPushRequest;

class DeleteNotificationPushRequestFactory
{
    public function createFromRequest(DeleteNotificationPushRequest $request): DeleteNotificationPushRequestEntity
    {
        return new DeleteNotificationPushRequestEntity(
            id: (int) $request->route('id')
        );
    }
}