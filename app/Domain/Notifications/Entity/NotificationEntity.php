<?php

namespace App\Domain\Notifications\Entity;

class NotificationEntity
{
    public function __construct(
        public ?int $page = null,
        public ?int $limit = null,
        public ?string $search = null,
        public ?string $type = null,
        public ?string $senderType = null,
        public ?string $direction = null,
        public ?string $sort = null,
        public array $notifications = [],
        public array $pagination = [],
        public int $statusCode = 200,
    ) {
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }

    public function getPagination(): array
    {
        return $this->pagination;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function setDirection(?string $direction): void
    {
        $this->direction = $direction;
    }

    public function setSort(?string $sort): void
    {
        $this->sort = $sort;
    }

    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function setPagination(array $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getSenderType(): ?string
    {
        return $this->senderType;
    }

    public function setSenderType(?string $senderType): void
    {
        $this->senderType = $senderType;
    }
}
