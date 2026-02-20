<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\AuthRequestEntity;
use App\Domain\Auth\Entity\AuthEntity;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use App\Enums\StatusCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
use App\Models\EmailVerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginVerificationCode;
use Carbon\Carbon;
use App\Models\User;
use App\Components\PointBonusComponent;

final class LoginUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(AuthRequestEntity $requestEntity): AuthEntity|array
    {
        try {
            $email = $requestEntity->getEmail();

            $userIndb = $this->userRepository->findByEmail($email);

            if (!$userIndb) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.user_not_found'), StatusCode::NOT_FOUND)
                );
            }

            $isSystemAdmin = $userIndb->isSystemAdmin();
            $credentials = $requestEntity->getCredentials();

            if (!auth()->validate($credentials)) {
                throw new HttpResponseException(
                    Response::apiError("ユーザー名とパスワードが一致しません。", StatusCode::UNAUTHORIZED)
                );
            }

            if ($isSystemAdmin) {
                if (config('auth.enable_email_2fa', false) && !$requestEntity->getVerificationCode()) {
                    return $this->handleTwoFactorAuthentication($email);
                }

                if (config('auth.enable_email_2fa', false) && $requestEntity->getVerificationCode()) {
                    $this->verifyTwoFactorCode($email, $requestEntity->getVerificationCode());
                }
            }

            $token = auth()->attempt($credentials);

            if (!$token) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.user_password_failed'), StatusCode::UNAUTHORIZED)
                );
            }

            $user = auth()->user();

            if (!$user) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.user_not_found'), StatusCode::UNAUTHORIZED)
                );
            }
            PointBonusComponent::dailyBonus();

            return new AuthEntity(
                token: $token
            );
        } catch (JWTException $e) {
            Log::error('JWT Exception:', ['message' => $e->getMessage()]);
            throw new HttpResponseException(
                Response::apiError($e->getMessage(), StatusCode::UNAUTHORIZED)
            );
        }
    }

    private function handleTwoFactorAuthentication(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new HttpResponseException(
                Response::apiError(__('auth.login.user_not_found'), StatusCode::NOT_FOUND)
            );
        }

        $code = str_pad(
            (string)random_int(0, 999999),
            config('auth.email_2fa.code_length', 6),
            '0',
            STR_PAD_LEFT
        );

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'email' => $email,
            'code' => $code,
            'type' => 'login_2fa',
            'expires_at' => Carbon::now()->addMinutes(
                (int)config('auth.email_2fa.code_expires_in', 10)
            )
        ]);

        Mail::to($email)->send(new LoginVerificationCode($code, $user->full_name));

        return [
            'message' => __('auth.login.verification_code_sent'),
            'requires_verification' => true,
            'status_code' => StatusCode::OK
        ];
    }

    private function verifyTwoFactorCode(string $email, string $code): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new HttpResponseException(
                Response::apiError(__('auth.login.user_not_found'), StatusCode::UNAUTHORIZED)
            );
        }

        $verificationCode = EmailVerificationCode::where([
            'user_id' => $user->id,
            'code' => $code,
            'type' => 'login_2fa',
            'is_used' => false
        ])
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verificationCode) {
            throw new HttpResponseException(
                Response::apiError(__('auth.login.verification_code_invalid'), StatusCode::UNAUTHORIZED)
            );
        }

        $verificationCode->update(['is_used' => true]);
    }
}
