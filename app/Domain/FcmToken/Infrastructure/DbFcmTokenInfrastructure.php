<?php

namespace App\Domain\FcmToken\Infrastructure;

use App\Domain\FcmToken\Entity\CreateFcmTokenRequestEntity;
use App\Domain\FcmToken\Entity\UpdateFcmTokenStatusRequestEntity;
use App\Domain\FcmToken\Entity\DeleteFcmTokenRequestEntity;
use App\Domain\FcmToken\Repository\FcmTokenRepository;
use App\Models\AppVersion;
use App\Models\FcmToken;

class DbFcmTokenInfrastructure implements FcmTokenRepository
{
    private FcmToken $fcmToken;

    public function __construct(FcmToken $fcmToken)
    {
        $this->fcmToken = $fcmToken;
    }

    public function store(CreateFcmTokenRequestEntity $requestEntity): bool
    {
        $fcmToken = FcmToken::where('fcm_token', $requestEntity->fcm_token)->first();
        $appVersionId = AppVersion::where('version_name', $requestEntity->app_version_name)->first()?->id ?? null;

        if (!$fcmToken) {
            $fcmToken = new FcmToken();
            $fcmToken->fcm_token = $requestEntity->fcm_token;
        }

        $fcmToken->user_id  = $requestEntity->user_id;
        $fcmToken->device_name  = $requestEntity->device_name;
        $fcmToken->app_id = $requestEntity->app_id;   
        $fcmToken->app_version_id = $appVersionId;

        return $fcmToken->save();
    }
    public function getByUserId(int $userId): array
    {
        $userId = auth()->user()->id;

        return $this->fcmToken
            ->select('fcm_tokens.*', 'app_versions.version_name')
            ->leftJoin('app_versions', 'app_versions.id', '=', 'fcm_tokens.app_version_id')
            ->where('fcm_tokens.user_id', $userId)
            ->orderBy('fcm_tokens.id', 'DESC')
            ->get()
            ->toArray();
    }
    public function updateStatus(UpdateFcmTokenStatusRequestEntity $entity): bool
    {
        $token = $this->fcmToken->find($entity->fcmTokenId);

        if (!$token) {
            return false;
        }

        $token->active_status = $entity->activeStatus;
        return $token->save();
    }

    public function delete(DeleteFcmTokenRequestEntity $requestEntity): bool
    {
        $deleted = FcmToken::where('fcm_token', $requestEntity->fcm_token)->delete();
        return $deleted > 0;
    }
}
