<?php

namespace App\Domain\Users\Entity;

class UsersEntity
{
    public function __construct(
        private ?string $searchEmail = null,
        private ?bool $searchEmailNot = null,
        private ?bool $searchEmailLike = null,
        private ?bool $admin = null,
        private ?string $sortFirstName = null,
        private ?string $sortEmail = null,
        private ?string $sortCreated = null,
        private ?string $sortUpdated = null,
        private ?string $search = null,
        private ?array $sortOrder = null,
        private array $users = [],
        private ?int $page = null,
        private ?int $limit = null,
        private array $pagination = [],
        private int $statusCode = 0
    ) {
    }

    public function getSortOrder(): ?array
    {
        return $this->sortOrder;
    }

    public function getSearchEmail(): ?string
    {
        return $this->searchEmail;
    }

    public function getSearchEmailNot(): ?bool
    {
        return $this->searchEmailNot;
    }

    public function getSearchEmailLike(): ?bool
    {
        return $this->searchEmailLike;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getSortFirstName(): ?string
    {
        return $this->sortFirstName;
    }

    public function getSortEmail(): ?string
    {
        return $this->sortEmail;
    }

    public function getSortCreated(): ?string
    {
        return $this->sortCreated;
    }

    public function getSortUpdated(): ?string
    {
        return $this->sortUpdated;
    }

    public function getSearch(): ?string
    {
        return $this->search;
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
