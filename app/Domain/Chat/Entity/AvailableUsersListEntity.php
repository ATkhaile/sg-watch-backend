<?php

namespace App\Domain\Chat\Entity;

class AvailableUsersListEntity implements \JsonSerializable
{
    private array $users;
    private array $pagination;
    private int $statusCode;
    private ?int $userId = null;
    private ?string $search = null;
    private ?int $limit = null;
    private ?int $page = null;
    private ?string $messageSearch = null;

    public function __construct(
        array $users = [],
        array $pagination = [],
        int $statusCode = 200,
        ?int $userId = null,
        ?string $search = null,
        ?int $limit = null,
        ?int $page = null,
        ?string $messageSearch = null
    ) {
        $this->users = $users;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
        $this->userId = $userId;
        $this->search = $search;
        $this->limit = $limit;
        $this->page = $page;
        $this->messageSearch = $messageSearch;
    }

    public function jsonSerialize(): array
    {
        return [
            'users' => $this->users,
            'pagination' => $this->pagination,
            'status_code' => $this->statusCode
        ];
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function getPagination(): array
    {
        return $this->pagination;
    }

    public function setPagination(array $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function setStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getMessageSearch(): ?string
    {
        return $this->messageSearch;
    }
}
