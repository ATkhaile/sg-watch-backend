<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\{UserEntity, EmailVerificationEntity, StatusEntity};
use App\Enums\StatusCode;
use App\Domain\Auth\Entity\RegisterUserRequestEntity;
use App\Exceptions\Domain\ErrorResourceException;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyRegistration;
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
            $token = Str::random(64);
            $timeout = (int)config('auth.email_verification.timeout', 15);
            $expiresAt = Carbon::now()->addMinutes($timeout);

            $inviterId = null;
            $inviteCode = $request->getInviteCode();
            if ($inviteCode) {
                $inviter = $this->userRepository->findByInviteCode($inviteCode);
                $inviterId = $inviter ? $inviter->getId() : null;
            }

            $verificationEntity = new EmailVerificationEntity(
                email: $request->getEmail(),
                firstName: $request->getFirstName(),
                lastName: $request->getLastName(),
                password: $request->getPassword(),
                token: $token,
                expiresAt: $expiresAt,
                inviterId: $inviterId
            );

            if (!$this->userRepository->createEmailVerification($verificationEntity)) {
                Log::error('Failed to create email verification');
                throw new ErrorResourceException(message: __('auth.register.failed'));
            }

            $baseVerifyUrl = env('BASE_FRONTEND_URL_VERIFY_REGISTRATION');
            $verificationUrl = rtrim($baseVerifyUrl, '/') . '?token=' . $token;

            Mail::to($request->getEmail())->send(new VerifyRegistration([
                'name' => $request->getFirstName() . ' ' . $request->getLastName(),
                'verificationUrl' => $verificationUrl
            ]));

            return new StatusEntity(
                statusCode: StatusCode::OK,
                message: __('auth.register.email_verification_sent'),
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
