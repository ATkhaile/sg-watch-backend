<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Google\Entity\GoogleAppLoginResponseEntity;
use App\Domain\Google\Repository\GoogleRepository;
use App\Enums\StatusCode;
use App\Domain\Auth\Entity\SessionAuthRequestEntity;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use App\Domain\Auth\Repository\UserRepository;
use App\Domain\Auth\Entity\AuthEntity;

final class SessionAppLoginUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(SessionAuthRequestEntity $request): AuthEntity
    {
        try {
            $userId = Cache::get("web_session_{$request->sessionId}");
            if (!$userId) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.user_not_found'), StatusCode::NOT_FOUND)
                );
            }
            $token = $this->userRepository->sessionLogin($userId);
            if (!$token) {
                throw new HttpResponseException(
                    Response::apiError(__('auth.login.user_not_found'), StatusCode::NOT_FOUND)
                );
            }
            Cache::forget("web_session_{$request->sessionId}");
            return new AuthEntity(
                token: $token
            );
        } catch (\Throwable $e) {
            throw new HttpResponseException(
                Response::apiError(__('auth.login.user_not_found'), StatusCode::NOT_FOUND)
            );
        }
    }
}