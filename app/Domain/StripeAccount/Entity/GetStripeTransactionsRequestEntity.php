<?php

namespace App\Domain\StripeAccount\Entity;

class GetStripeTransactionsRequestEntity
{
    private string $stripeAccountId;
    private ?int $limit;
    private ?string $startingAfter;
    private ?string $endingBefore;
    private ?int $created;
    private ?string $startDate;
    private ?string $endDate;

    public function __construct(
        string $stripeAccountId,
        ?int $limit = null,
        ?string $startingAfter = null,
        ?string $endingBefore = null,
        ?int $created = null,
        ?string $startDate = null,
        ?string $endDate = null
    ) {
        $this->stripeAccountId = $stripeAccountId;
        $this->limit = $limit;
        $this->startingAfter = $startingAfter;
        $this->endingBefore = $endingBefore;
        $this->created = $created;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }
}
