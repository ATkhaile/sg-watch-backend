<?php

namespace App\Domain\StripeAccount\Entity;

class StripeAccountEntity
{
    private ?int $searchUserId;
    private ?string $searchName;
    private ?bool $searchNameNot;
    private ?bool $searchNameLike;
    private ?string $filterStatus;
    private ?int $page;
    private ?int $limit;
    private ?string $sortName;
    private ?string $sortCreated;
    private ?string $sortUpdated;
    private ?array $sortOrder = null;
    private ?string $activeUserStripeAccountId;
    private array $stripeAccounts;
    private array $pagination;
    private int $statusCode;

    public function __construct(
        ?int $searchUserId = null,
        ?string $searchName = null,
        ?bool $searchNameNot = null,
        ?bool $searchNameLike = null,
        ?string $filterStatus = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $sortName = null,
        ?string $sortCreated = null,
        ?string $sortUpdated = null,
        ?array $sortOrder = null,
        ?string $activeUserStripeAccountId = null,
        array $stripeAccounts = [],
        array $pagination = [],
        int $statusCode = 0
    ) {
        $this->searchUserId = $searchUserId;
        $this->searchName = $searchName;
        $this->searchNameNot = $searchNameNot;
        $this->searchNameLike = $searchNameLike;
        $this->filterStatus = $filterStatus;
        $this->page = $page;
        $this->limit = $limit;
        $this->sortName = $sortName;
        $this->sortCreated = $sortCreated;
        $this->sortUpdated = $sortUpdated;
        $this->sortOrder = $sortOrder;
        $this->activeUserStripeAccountId = $activeUserStripeAccountId;
        $this->stripeAccounts = $stripeAccounts;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
    }

    public function getSortOrder(): ?array
    {
        return $this->sortOrder;
    }

    public function getActiveUserStripeAccountId(): ?string
    {
        return $this->activeUserStripeAccountId;
    }

    public function getSearchUserId(): ?int
    {
        return $this->searchUserId;
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

    public function getFilterStatus(): ?string
    {
        return $this->filterStatus;
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

    public function setStripeAccount(array $stripeAccounts): void
    {
        $this->stripeAccounts = $stripeAccounts;
    }

    public function getStripeAccount(): array
    {
        return $this->stripeAccounts;
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
