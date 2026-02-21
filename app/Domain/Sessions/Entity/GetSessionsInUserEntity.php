<?php

namespace App\Domain\Sessions\Entity;

use App\Enums\StatusCode;

class GetSessionsInUserEntity
{
    private array $data = [];
    private array $pagination = [];
    private int $statusCode = StatusCode::OK;
    private ?string $filterStatus = null;
    private string $sortBy = 'id';
    private string $sortDirection = 'desc';
    private int $perPage = 10;
    private int $page = 1;

    public function __construct(
        ?string $filterStatus = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc',
        int $perPage = 10,
        int $page = 1
    ) {
        $this->filterStatus = $filterStatus;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->perPage = $perPage;
        $this->page = $page;
    }

    public function setData(array $data): void { $this->data = $data; }
    public function getData(): array { return $this->data; }
    public function setPagination(array $pagination): void { $this->pagination = $pagination; }
    public function getPagination(): array { return $this->pagination; }
    public function setStatusCode(int $statusCode): void { $this->statusCode = $statusCode; }
    public function getStatusCode(): int { return $this->statusCode; }
    public function setFilterStatus(?string $status): void { $this->filterStatus = $status; }
    public function getFilterStatus(): ?string { return $this->filterStatus; }
    public function setSortBy(string $sortBy): void { $this->sortBy = $sortBy; }
    public function getSortBy(): string { return $this->sortBy; }
    public function setSortDirection(string $sortDirection): void { $this->sortDirection = $sortDirection; }
    public function getSortDirection(): string { return $this->sortDirection; }
    public function setPerPage(int $perPage): void { $this->perPage = $perPage; }
    public function getPerPage(): int { return $this->perPage; }
    public function setPage(int $page): void { $this->page = $page; }
    public function getPage(): int { return $this->page; }
}
