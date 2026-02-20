<?php

namespace App\Domain\StripeAccount\Entity;

class StripePaymentLinkEntity
{
    private array $paymentLinks;
    private ?string $hasMore;
    private ?string $nextCursor;
    private int $statusCode;
    private int $total;

    public function __construct(
        array $paymentLinks = [],
        ?string $hasMore = null,
        ?string $nextCursor = null,
        int $statusCode = 200,
        int $total = 0
    ) {
        $this->paymentLinks = $paymentLinks;
        $this->hasMore = $hasMore;
        $this->nextCursor = $nextCursor;
        $this->statusCode = $statusCode;
        $this->total = $total;
    }

    public function getPaymentLinks(): array
    {
        return $this->paymentLinks;
    }

    public function setPaymentLinks(array $paymentLinks): void
    {
        $this->paymentLinks = $paymentLinks;
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

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function jsonSerialize(): array
    {
        return [
            'payment_links' => $this->paymentLinks,
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
