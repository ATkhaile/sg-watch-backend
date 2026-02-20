<?php

namespace App\Domain\Firebase\Entity;

class FirebaseNotificationEntity
{
    public function __construct(
        public ?int $page = null,
        public ?int $limit = null,
        public ?string $fcm_token = null,
        public array $notifications = [],
        public array $pagination = [],
        public int $statusCode = 200
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

    public function getFcmToken(): ?string
    {
        return $this->fcm_token;
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

    public function setFcmToken(?string $fcm_token): void
    {
        $this->fcm_token = $fcm_token;
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
}
