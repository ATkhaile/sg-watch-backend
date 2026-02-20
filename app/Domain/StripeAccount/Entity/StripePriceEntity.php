<?php

namespace App\Domain\StripeAccount\Entity;

class StripePriceEntity
{
    private array $prices;
    private ?string $hasMore;
    private ?string $nextCursor;
    private int $statusCode;

    public function __construct(
        array $prices = [],
        ?string $hasMore = null,
        ?string $nextCursor = null,
        int $statusCode = 200
    ) {
        $this->prices = $prices;
        $this->hasMore = $hasMore;
        $this->nextCursor = $nextCursor;
        $this->statusCode = $statusCode;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): void
    {
        $this->prices = $prices;
    }

    public function getHasMore(): ?string
    {
        return $this->hasMore;
    }

    public function setHasMore(?string $hasMore): void
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

    public function jsonSerialize(): array
    {
        return [
            'prices' => $this->prices,
            'has_more' => $this->hasMore,
            'next_cursor' => $this->nextCursor,
        ];
    }

    public function getStatus(): int
    {
        return $this->statusCode;
    }
}
