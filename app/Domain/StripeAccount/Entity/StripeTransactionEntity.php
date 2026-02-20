<?php

namespace App\Domain\StripeAccount\Entity;

class StripeTransactionEntity
{
    private array $transactions;
    private bool $hasMore;
    private ?string $nextCursor;
    private int $statusCode;
    private int $total;
    private ?array $todayStats;

    public function __construct(
        array $transactions = [],
        bool $hasMore = false,
        ?string $nextCursor = null,
        int $statusCode = 200,
        int $total = 0
    ) {
        $this->transactions = $transactions;
        $this->hasMore = $hasMore;
        $this->nextCursor = $nextCursor;
        $this->statusCode = $statusCode;
        $this->total = $total;
        $this->todayStats = null;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }

    public function getHasMore(): bool
    {
        return $this->hasMore;
    }

    public function setHasMore(bool $hasMore): void
    {
        $this->hasMore = $hasMore;
    }

    public function getNextCursor(): ?string
    {
        return $this->nextCursor;
    }

    public function setNextCursor(?string $nextCursor): void
    {
        $this->nextCursor = $nextCursor;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getTodayStats(): ?array
    {
        return $this->todayStats;
    }

    public function setTodayStats(array $todayStats): void
    {
        $this->todayStats = $todayStats;
    }

    public function jsonSerialize(): array
    {
        return [
            'transactions' => $this->transactions,
            'total' => $this->total,
            'has_more' => $this->hasMore,
            'next_cursor' => $this->nextCursor,
        ];
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
