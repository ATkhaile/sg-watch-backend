<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\StatusEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Enums\StatusCode;
use App\Exceptions\Domain\ErrorResourceException;
use Illuminate\Support\Facades\DB;

final class VerifyRegistrationOtpUseCase
{
    private const MAX_ATTEMPTS = 5;

    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function __invoke(string $email, string $code): StatusEntity
    {
        try {
            $otpRecord = $this->userRepository->findValidRegistrationOtp($email);

            if (!$otpRecord) {
                throw new ErrorResourceException(message: __('auth.register.otp_expired'));
            }

            if ($otpRecord['attempts'] >= self::MAX_ATTEMPTS) {
                $this->userRepository->markOtpAsUsed($otpRecord['id']);
                throw new ErrorResourceException(message: __('auth.register.otp_max_attempts'));
            }

            if ($otpRecord['code'] !== $code) {
                $this->userRepository->incrementOtpAttempts($otpRecord['id']);
                throw new ErrorResourceException(message: __('auth.register.otp_invalid'));
            }

            DB::beginTransaction();

            $this->userRepository->markOtpAsUsed($otpRecord['id']);

            $verification = $this->userRepository->findEmailVerificationByEmail($email);

            if (!$verification) {
                DB::rollBack();
                throw new ErrorResourceException(message: __('auth.register.verification_not_found'));
            }

            $userEntity = $this->userRepository->completeRegistration($verification);

            if (!$userEntity) {
                DB::rollBack();
                throw new ErrorResourceException(message: __('auth.register.failed'));
            }

            DB::commit();

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.register.otp_verified'),
            );
        } catch (ErrorResourceException $e) {
            throw $e;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new ErrorResourceException(message: $th->getMessage());
        }
    }
}
