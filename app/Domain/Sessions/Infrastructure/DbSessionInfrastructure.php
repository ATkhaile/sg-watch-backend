<?php

namespace App\Domain\Sessions\Infrastructure;

use App\Domain\Sessions\Entity\GetAllSessionsEntity;
use App\Domain\Sessions\Entity\GetSessionsInUserEntity;
use App\Domain\Sessions\Entity\SessionDetailEntity;
use App\Domain\Sessions\Repository\SessionRepository;
use Illuminate\Support\Facades\DB;
use App\Domain\Sessions\Entity\CreateSessionRequestEntity;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DbSessionInfrastructure implements SessionRepository
{
    public function store(CreateSessionRequestEntity $requestEntity): bool
    {
        try {
            DB::table('sessions')->insert([
                'user_id' => $requestEntity->getUserId(),
                'token_hash' => hash('sha256', $requestEntity->getToken()),
                'ip_address' => $requestEntity->getIpAddress(),
                'user_agent' => $requestEntity->getUserAgent(),
                'app_id' => $requestEntity->getAppId(),
                'domain' => $requestEntity->getDomain(),
                'last_activity' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getSessionById(int $sessionId): ?SessionDetailEntity
    {
        try {
            $session = DB::table('sessions')
                ->select([
                    'sessions.id',
                    'sessions.user_id',
                    'sessions.token_hash',
                    'sessions.ip_address',
                    'sessions.user_agent',
                    'sessions.last_activity',
                    'sessions.is_active'
                ])
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return null;
            }

            return new SessionDetailEntity(
                id: $session->id,
                userId: $session->user_id,
                token: $session->token_hash,
                ipAddress: $session->ip_address,
                userAgent: $session->user_agent,
                lastActivity: new \DateTime($session->last_activity),
                isActive: $session->is_active,
                statusCode: 200
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function invalidateSession(int $sessionId): bool
    {
        return DB::table('sessions')
            ->where('id', $sessionId)
            ->update(['is_active' => false]) > 0;
    }

    public function getAllSessions(GetAllSessionsEntity $entity): LengthAwarePaginator
    {
        $query = DB::table('sessions');

        if ($entity->getUserId()) {
            $query->where('user_id', $entity->getUserId());
        }

        if ($entity->getFilterStatus()) {
            $query->where('is_active', $entity->getFilterStatus() === 'active');
        }

        $query->orderBy($entity->getSortBy(), $entity->getSortDirection());

        return $query->paginate(
            $entity->getPerPage(),
            ['*'],
            'page',
            $entity->getPage()
        );
    }

    public function getSessionsInUser(GetSessionsInUserEntity $entity): LengthAwarePaginator
    {
        $query = DB::table('sessions')->where('user_id', auth()->user()->id);

        if ($entity->getFilterStatus()) {
            $query->where('is_active', $entity->getFilterStatus() === 'active');
        }

        $query->orderBy($entity->getSortBy(), $entity->getSortDirection());

        return $query->paginate(
            $entity->getPerPage(),
            ['*'],
            'page',
            $entity->getPage()
        );
    }

    public function invalidateSessionInUser(int $sessionId): bool
    {
        return DB::table('sessions')
            ->where('user_id', auth()->user()->id)
            ->where('id', $sessionId)
            ->update(['is_active' => false]) > 0;
    }

    public function getUserFromJwtToken(string $token): ?object
    {
        try {
            $payload = \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->getPayload();
            $userId = $payload->get('sub');
            return User::where('id', $userId)->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
