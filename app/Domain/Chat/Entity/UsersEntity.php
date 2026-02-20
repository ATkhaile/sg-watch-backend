<?php

namespace App\Domain\Chat\Entity;

class UsersEntity implements \JsonSerializable
{
    private array $users;
    private array $pagination;
    private int $statusCode;
    private ?string $search = null;
    private ?int $page = null;
    private ?int $limit = null;

    public function __construct(
        array $users = [],
        array $pagination = [],
        int $statusCode = 200,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null
    ) {
        $this->users = $users;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
        $this->search = $search;
        $this->page = $page;
        $this->limit = $limit;
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

    public function getPagination(): array
    {
        return $this->pagination;
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function setPagination(array $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    public function setStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
}