<?php

namespace App\Domain\StripeAccount\Entity;

class GetStripePricesRequestEntity
{
    private string $stripeAccountId;
    private ?string $productId;
    private ?int $limit;
    private ?string $startingAfter;
    private ?string $endingBefore;
    private ?bool $active;

    public function __construct(
        string $stripeAccountId,
        ?string $productId = null,
        ?int $limit = null,
        ?string $startingAfter = null,
        ?string $endingBefore = null,
        ?bool $active = null
    ) {
        $this->stripeAccountId = $stripeAccountId;
        $this->productId = $productId;
        $this->limit = $limit;
        $this->startingAfter = $startingAfter;
        $this->endingBefore = $endingBefore;
        $this->active = $active;
    }

    public function getStripeAccountId(): string
    {
        return $this->stripeAccountId;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getStartingAfter(): ?string
    {
        return $this->startingAfter;
    }

    public function getEndingBefore(): ?string
    {
        return $this->endingBefore;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }
}
