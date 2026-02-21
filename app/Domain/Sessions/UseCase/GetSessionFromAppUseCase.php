<?php

namespace App\Domain\Sessions\UseCase;

use App\Domain\Sessions\Repository\SessionRepository;
use App\Enums\StatusCode;
use Illuminate\Http\Request;
use App\Exceptions\Domain\NotFoundResourceException;
use Illuminate\Support\Facades\Cache;

final class GetSessionFromAppUseCase
{
    public function __construct(
        private SessionRepository $repository
    ) {}

    public function __invoke(Request $request): array
    {
        $user = $this->getUserFromBearer($request);
        if (!$user) {
            throw new NotFoundResourceException(message: __('messages.error'));
        }

        return $this->createWebSession($user);
    }

    private function createWebSession($user): array
    {
        $sessionToken = base64_encode(random_bytes(32));

        Cache::put("web_session_{$sessionToken}", $user->id, now()->addHours(4));

        $cookie = cookie(
            name: 'session',
            value: $sessionToken,
            minutes: 240,
            path: '/',
            domain: config('app.frontend_url'),
            secure: true,
            httpOnly: true,
            sameSite: 'Lax'
        );

        return [
            'status_code' => StatusCode::OK,
            'message' => 'Session created successfully',
            'cookie' => $cookie
        ];
    }

    private function getUserFromBearer(Request $request)
    {
        $header = $request->header('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $token = substr($header, 7);

        try {
            return $this->repository->getUserFromJwtToken($token);
        } catch (\Exception $e) {
            return null;
        }
    }
}
