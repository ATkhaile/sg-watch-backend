<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Entity\VerifyLoginRequestEntity;
use App\Domain\Auth\Entity\AuthEntity;
use App\Domain\Auth\Repository\UserRepository;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
use App\Enums\StatusCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

final class VerifyLoginUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(VerifyLoginRequestEntity $requestEntity): AuthEntity
    {
        try {
            $credentials = $requestEntity->getCredentials();

            $user = null;
            if (isset($credentials['email'])) {
                $user = User::where('email', $credentials['email'])->first();
            }

            if (!$user) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.notfound'), StatusCode::UNAUTHORIZED)
                );
            }

            $verificationCode = EmailVerificationCode::where([
                'user_id' => $user->id,
                'code' => $requestEntity->getVerificationCode(),
                'type' => 'login_2fa',
                'is_used' => false
            ])
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$verificationCode) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.token_failed'), StatusCode::UNAUTHORIZED)
                );
            }

            $verificationCode->update(['is_used' => true]);

            $token = auth()->login($user);
            if (!$token) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.token_expired'), StatusCode::UNAUTHORIZED)
                );
            }

            return new AuthEntity(token: $token);
        } catch (JWTException $e) {
            Log::error('JWT Exception:', ['message' => $e->getMessage()]);
            throw new HttpResponseException(
                Response::apiError($e->getMessage(), StatusCode::UNAUTHORIZED)
            );
        }
    }
}
