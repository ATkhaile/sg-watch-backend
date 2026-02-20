<?php

namespace App\Domain\Chat\Entity;

class ChatMessageListEntity
{
    private array $messages;
    private array $pagination;
    private int $statusCode;
    private ?int $userId = null;
    private ?int $receiverId = null;
    private ?int $limit = null;
    private ?int $page = null;
    private int $unreadCount = 0;

    public function __construct(
        array $messages = [],
        array $pagination = [],
        int $statusCode = 200,
        ?int $userId = null,
        ?int $receiverId = null,
        ?int $limit = null,
        ?int $page = null,
        int $unreadCount = 0
    ) {
        $this->messages = $messages;
        $this->pagination = $pagination;
        $this->statusCode = $statusCode;
        $this->userId = $userId;
        $this->receiverId = $receiverId;
        $this->limit = $limit;
        $this->page = $page;
        $this->unreadCount = $unreadCount;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }
    public function getPagination(): array
    {
        return $this->pagination;
    }
    public function setPagination(array $pagination): void
    {
        $this->pagination = $pagination;
    }
    public function getStatus(): int
    {
        return $this->statusCode;
    }
    public function setStatus(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
    public function getUserId(): ?int
    {
        return $this->userId;
    }
    public function getReceiverId(): ?int
    {
        return $this->receiverId;
    }
    public function getLimit(): ?int
    {
        return $this->limit;
    }
    public function getPage(): ?int
    {
        return $this->page;
    }
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }
    public function setReceiverId(?int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }
    public function getUnreadCount(): int
    {
        return $this->unreadCount;
    }
    public function setUnreadCount(int $unreadCount): void
    {
        $this->unreadCount = $unreadCount;
    }
}
