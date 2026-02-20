<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\StatusEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Enums\StatusCode;
use App\Exceptions\Domain\ErrorResourceException;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

final class VerifyRegistrationUseCase
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(string $token): StatusEntity
    {
        try {
            DB::beginTransaction();

            $verification = $this->userRepository->findEmailVerification($token);

            if (!$verification) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.invalid_token'), StatusCode::NOT_FOUND)
                );
            }

            if (Carbon::parse($verification->getExpiresAt())->isPast()) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.expired_token'), StatusCode::BAD_REQUEST)
                );
            }

            $userEntity = $this->userRepository->completeRegistration($verification);

            if (!$userEntity) {
                throw new ErrorResourceException;
            }

            DB::commit();

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.register.email_verification_success')
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
