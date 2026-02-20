<?php

namespace App\Domain\Authorization\Entity;

class PermissionsEntity
{
    private ?string $searchName;
    private ?bool $searchNameNot;
    private ?bool $searchNameLike;
    private ?string $searchUsecaseGroup;
    private ?bool $searchUsecaseGroupNot;
    private ?bool $searchUsecaseGroupLike;
    private ?string $usecaseGroup;
    private ?int $page;
    private ?int $limit;
    private ?string $sortName;
    private ?string $sortUsecaseGroup;
    private ?string $sortCreated;
    private ?string $sortUpdated;
    private ?array $sortOrder = null;
    private array $permissions;
    private array $usecaseGroups;
    private array $pagination;
    private int $statusCode;

    public function __construct(
        ?string $searchName = null,
        ?bool $searchNameNot = null,
        ?bool $searchNameLike = null,
        ?string $searchUsecaseGroup = null,
        ?bool $searchUsecaseGroupNot = null,
        ?bool $searchUsecaseGroupLike = null,
        ?string $usecaseGroup = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $sortName = null,
        ?string $sortUsecaseGroup = null,
        ?string $sortCreated = null,
        ?string $sortUpdated = null,
        ?array $sortOrder = null,
        array $permissions = [],
        array $usecaseGroups = [],
        array $pagination = [],
        int $statusCode = 0
    ) {
        $this->searchName = $searchName;
        $this->searchNameNot = $searchNameNot;
        $this->searchNameLike = $searchNameLike;
        $this->searchUsecaseGroup = $searchUsecaseGroup;
        $this->searchUsecaseGroupNot = $searchUsecaseGroupNot;
        $this->searchUsecaseGroupLike = $searchUsecaseGroupLike;
        $this->usecaseGroup = $usecaseGroup;
        $this->page = $page;
        $this->limit = $limit;
        $this->sortName = $sortName;
        $this->sortUsecaseGroup = $sortUsecaseGroup;
        $this->sortCreated = $sortCreated;
        $this->sortUpdated = $sortUpdated;
        $this->sortOrder = $sortOrder;
        $this->permissions = $permissions;
        $this->usecaseGroups = $usecaseGroups;
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

    public function getSearchUsecaseGroup(): ?string
    {
        return $this->searchUsecaseGroup;
    }

    public function getSearchUsecaseGroupNot(): ?bool
    {
        return $this->searchUsecaseGroupNot;
    }

    public function getSearchUsecaseGroupLike(): ?bool
    {
        return $this->searchUsecaseGroupLike;
    }

    public function getUsecaseGroup(): ?string
    {
        return $this->usecaseGroup;
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

    public function getSortUsecaseGroup(): ?string
    {
        return $this->sortUsecaseGroup;
    }

    public function getSortCreated(): ?string
    {
        return $this->sortCreated;
    }

    public function getSortUpdated(): ?string
    {
        return $this->sortUpdated;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setUsecaseGroups(array $usecaseGroups): void
    {
        $this->usecaseGroups = $usecaseGroups;
    }

    public function getUsecaseGroups(): array
    {
        return $this->usecaseGroups;
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
