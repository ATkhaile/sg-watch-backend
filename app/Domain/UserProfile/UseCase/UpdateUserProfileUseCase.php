<?php

namespace App\Domain\UserProfile\UseCase;

use App\Domain\UserProfile\Entity\UpdateUserProfileRequestEntity;
use App\Domain\UserProfile\Entity\StatusEntity;
use App\Domain\UserProfile\Repository\UserProfileRepository;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Auth;
use App\Mail\CompleteMemberRegister;
use Illuminate\Support\Facades\Mail;

final class UpdateUserProfileUseCase
{
    public function __construct(
        private UserProfileRepository $userProfileRepository
    ) {
    }

    public function __invoke(int $accountId, UpdateUserProfileRequestEntity $entity): StatusEntity
    {
        $nameOld = Auth::guard('member')->user()->last_name_kana;
        if (!$this->userProfileRepository->update($accountId, $entity)) {
            return new StatusEntity(
                statusCode: StatusCode::INTERNAL_ERR,
                message: __('user_profile.update.failed')
            );
        }

        if (!$nameOld) {
            $email = Auth::guard('member')->user()->email;
            $data = $entity->toArray();
            $data['email'] = $email;
            Mail::to($entity->emailSend)->bcc([env('MAIL_ADMIN'), env('MAIL_ADMIN1')])->send(new CompleteMemberRegister($data));
        }
        return new StatusEntity(
            statusCode: StatusCode::OK,
            message: __('user_profile.update.success')
        );
    }
}
