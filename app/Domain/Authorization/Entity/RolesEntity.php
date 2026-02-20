<?php

namespace App\Domain\Authorization\Entity;

class RolesEntity
{
    private ?string $searchName;
    private ?bool $searchNameNot;
    private ?bool $searchNameLike;
    private ?int $page;
    private ?int $limit;
    private ?string $sortName;
    private ?string $sortCreated;
    private ?string $sortUpdated;
    private ?array $sortOrder = null;
    private array $roles;
    private array $pagination;
    private int $statusCode;

    public function __construct(
        ?string $searchName = null,
        ?bool $searchNameNot = null,
        ?bool $searchNameLike = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $sortName = null,
        ?string $sortCreated = null,
        ?string $sortUpdated = null,
        ?array $sortOrder = null,
        array $roles = [],
        array $pagination = [],
        int $statusCode = 0
    ) {
        $this->searchName = $searchName;
        $this->searchNameNot = $searchNameNot;
        $this->searchNameLike = $searchNameLike;
        $this->page = $page;
        $this->limit = $limit;
        $this->sortName = $sortName;
        $this->sortCreated = $sortCreated;
        $this->sortUpdated = $sortUpdated;
        $this->sortOrder = $sortOrder;
        $this->roles = $roles;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
    }

    public function getSortOrder(): ?array
    {
        return $this->sortOrder;
    }

    public function getSearchName(): ?string
    {
        return $this->searchName;
    }

    public function getSearchNameNot(): ?bool
    {
        return $this->searchNameNot;
    }

    public function getSearchNameLike(): ?bool
    {
        return $this->searchNameLike;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getSortName(): ?string
    {
        return $this->sortName;
    }

    public function getSortCreated(): ?string
    {
        return $this->sortCreated;
    }

    public function getSortUpdated(): ?string
    {
        return $this->sortUpdated;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
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
