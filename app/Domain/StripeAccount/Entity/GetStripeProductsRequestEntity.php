<?php

namespace App\Domain\StripeAccount\Entity;

class GetStripeProductsRequestEntity
{
    private string $stripeAccountId;
    private ?int $limit;
    private ?string $startingAfter;
    private ?string $endingBefore;
    private ?bool $active;

    public function __construct(
        string $stripeAccountId,
        ?int $limit = null,
        ?string $startingAfter = null,
        ?string $endingBefore = null,
        ?bool $active = null
    ) {
        $this->stripeAccountId = $stripeAccountId;
        $this->limit = $limit;
        $this->startingAfter = $startingAfter;
        $this->endingBefore = $endingBefore;
        $this->active = $active;
    }

    public function getStripeAccountId(): string
    {
        return $this->stripeAccountId;
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
