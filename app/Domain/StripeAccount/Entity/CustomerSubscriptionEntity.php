<?php

namespace App\Domain\StripeAccount\Entity;

class CustomerSubscriptionEntity
{
    private ?int $page;
    private ?int $limit;
    private array $customers;
    private array $pagination;
    private int $statusCode;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        array $customers = [],
        array $pagination = [],
        int $statusCode = 0
    ) {
        $this->page = $page;
        $this->limit = $limit;
        $this->customers = $customers;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setCustomers(array $customers): void
    {
        $this->customers = $customers;
    }

    public function getCustomers(): array
    {
        return $this->customers;
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
