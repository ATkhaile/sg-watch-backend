<?php

namespace App\Domain\Sessions\Repository;

use App\Domain\Sessions\Entity\GetAllSessionsEntity;
use App\Domain\Sessions\Entity\GetSessionsInUserEntity;
use App\Domain\Sessions\Entity\SessionDetailEntity;
use App\Domain\Sessions\Entity\CreateSessionRequestEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SessionRepository
{
    public function store(CreateSessionRequestEntity $requestEntity): bool;
    public function getSessionById(int $sessionId): ?SessionDetailEntity;
    public function invalidateSession(int $sessionId): bool;
    public function getAllSessions(GetAllSessionsEntity $entity): LengthAwarePaginator;
    public function getSessionsInUser(GetSessionsInUserEntity $entity): LengthAwarePaginator;
    public function invalidateSessionInUser(int $sessionId): bool;
    public function getUserFromJwtToken(string $token): ?object;
}
