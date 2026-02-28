<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\{UserEntity, EmailVerificationEntity, StatusEntity};
use App\Enums\StatusCode;
use App\Domain\Auth\Entity\RegisterUserRequestEntity;
use App\Exceptions\Domain\ErrorResourceException;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtp;
use App\Mail\RegisterUser;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

final class RegisterUserUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(RegisterUserRequestEntity $request): StatusEntity
    {
        try {
            if (config('auth.email_verification.enabled')) {
                return $this->handleEmailVerification($request);
            }

            return $this->registerWithoutVerification($request);
        } catch (\Throwable $th) {
            throw new ErrorResourceException(message: $th->getMessage());
        }
    }

    private function handleEmailVerification(RegisterUserRequestEntity $request): StatusEntity
    {
        try {
            $expiresInSeconds = (int) config('auth.registration_otp.expires_in', 200);

            $inviterId = null;
            $inviteCode = $request->getInviteCode();
            if ($inviteCode) {
                $inviter = $this->userRepository->findByInviteCode($inviteCode);
                $inviterId = $inviter ? $inviter->getId() : null;
            }

            // Save registration data
            $token = Str::random(64);
            $verificationEntity = new EmailVerificationEntity(
                email: $request->getEmail(),
                firstName: $request->getFirstName(),
                lastName: $request->getLastName(),
                password: $request->getPassword(),
                token: $token,
                expiresAt: Carbon::now()->addSeconds($expiresInSeconds),
                inviterId: $inviterId
            );

            if (!$this->userRepository->createEmailVerification($verificationEntity)) {
                Log::error('Failed to create email verification');
                throw new ErrorResourceException(message: __('auth.register.failed'));
            }

            // Generate and send OTP
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $this->userRepository->invalidateRegistrationOtps($request->getEmail());
            $this->userRepository->createRegistrationOtp($request->getEmail(), $code, $expiresInSeconds);

            $name = $request->getFirstName() . ' ' . $request->getLastName();
            Mail::to($request->getEmail())->send(new RegistrationOtp($code, $name));

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.register.otp_sent'),
            );
        } catch (\Throwable $th) {
            throw new ErrorResourceException(message: $th->getMessage());
        }
    }

    private function registerWithoutVerification(RegisterUserRequestEntity $request): StatusEntity
    {
        try {
            $userEntity = new UserEntity(
                email: $request->getEmail(),
                firstName: $request->getFirstName(),
                lastName: $request->getLastName(),
                password: $request->getPassword(),
                inviteCode: $request->getInviteCode(),
            );

            if (!$this->userRepository->save($userEntity)) {
                throw new ErrorResourceException(message: __('auth.register.failed'));
            }

            $user = User::where('email', $request->getEmail())->first();
            $token = null;
            if ($user) {
                $token = JWTAuth::fromUser($user);

                try {
                    Mail::to($request->getEmail())->send(new RegisterUser([
                        'name' => $request->getFirstName() . ' ' . $request->getLastName(),
                        'email' => $request->getEmail(),
                    ]));
                } catch (\Throwable $mailError) {
                    Log::error('Failed to send registration email: ' . $mailError->getMessage());
                }
            }

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.register.success'),
                token: $token,
            );
        } catch (\Throwable $th) {
            throw new ErrorResourceException(message: $th->getMessage());
        }
    }
}
