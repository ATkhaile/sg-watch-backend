<?php

namespace App\Domain\Users\Entity;

class GetUsersWithStoriesEntity
{
    private array $users;
    private array $pagination;
    private int $statusCode;

    public function __construct(
        array $users = [],
        array $pagination = [],
        int $statusCode = 0
    ) {
        $this->users = $users;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
    }

    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setPagination(array $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): array
    {
        return $this->pagination;
    }

    public function setStatus(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
