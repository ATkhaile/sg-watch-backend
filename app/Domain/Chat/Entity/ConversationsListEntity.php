<?php

namespace App\Domain\Chat\Entity;

use App\Enums\StatusCode;

class ConversationsListEntity
{
    private int $userId;
    private int $page;
    private int $limit;
    private ?string $search;
    private ?string $messageSearch;
    private array $conversations = [];
    private array $pagination = [];
    private int $status;

    public function __construct(
        int $userId,
        int $page = 1,
        int $limit = 10,
        ?string $search = null,
        ?string $messageSearch = null
    ) {
        $this->userId = $userId;
        $this->page = $page;
        $this->limit = $limit;
        $this->search = $search;
        $this->messageSearch = $messageSearch;
        $this->status = StatusCode::OK;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getMessageSearch(): ?string
    {
        return $this->messageSearch;
    }

    public function getConversations(): array
    {
        return $this->conversations;
    }

    public function getPagination(): array
    {
        return $this->pagination;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setConversations(array $conversations): void
    {
        $this->conversations = $conversations;
    }

    public function setPagination(array $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
