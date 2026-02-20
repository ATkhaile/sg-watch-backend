<?php

namespace App\Domain\Comment\Entity;

class CommentsEntity
{
    private ?string $searchModel;
    private ?int $searchModelId;
    private ?string $searchContent;
    private ?int $page;
    private ?int $limit;
    private ?string $sortCreated;
    private ?string $sortUpdated;
    private ?array $sortOrder = null;
    private array $comments;
    private int $totalComments;
    private array $pagination;
    private int $statusCode;

    public function __construct(
        ?string $searchModel = null,
        ?int $searchModelId = null,
        ?string $searchContent = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $sortCreated = null,
        ?string $sortUpdated = null,
        ?array $sortOrder = null,
        array $comments = [],
        int $totalComments = 0,
        array $pagination = [],
        int $statusCode = 0
    ) {
        $this->searchModel = $searchModel;
        $this->searchModelId = $searchModelId;
        $this->searchContent = $searchContent;
        $this->page = $page;
        $this->limit = $limit;
        $this->sortCreated = $sortCreated;
        $this->sortUpdated = $sortUpdated;
        $this->sortOrder = $sortOrder;
        $this->comments = $comments;
        $this->totalComments = $totalComments;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
    }

    public function getSearchModel(): ?string
    {
        return $this->searchModel;
    }

    public function getSearchModelId(): ?int
    {
        return $this->searchModelId;
    }

    public function getSearchContent(): ?string
    {
        return $this->searchContent;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getSortCreated(): ?string
    {
        return $this->sortCreated;
    }

    public function getSortUpdated(): ?string
    {
        return $this->sortUpdated;
    }

    public function getSortOrder(): ?array
    {
        return $this->sortOrder;
    }

    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function setTotalComments(int $totalComments): void
    {
        $this->totalComments = $totalComments;
    }

    public function getTotalComments(): int
    {
        return $this->totalComments;
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
