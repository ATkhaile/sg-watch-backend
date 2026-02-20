<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\AuthEntity;
use App\Models\FcmToken;
use App\Models\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

final class LogoutUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(?string $fcmToken): AuthEntity
    {
        $token = JWTAuth::getToken();
        $tokenString = (string) $token;
        $tokenHash = hash('sha256', $tokenString);

        Session::where('token_hash', $tokenHash)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        JWTAuth::parseToken()->invalidate(true);
        \Session::flush();
         if ($fcmToken) {
            $userId = auth()->id();

            if ($userId) {
                FcmToken::where('fcm_token', $fcmToken)
                    ->where('user_id', $userId)
                    ->delete();
            }
        }
        return new AuthEntity(
            message: __('auth.logout.success'),
        );
    }
}
