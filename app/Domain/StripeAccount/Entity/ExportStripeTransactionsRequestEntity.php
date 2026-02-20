<?php

namespace App\Domain\StripeAccount\Entity;

class ExportStripeTransactionsRequestEntity
{
    private string $stripeAccountId;
    private ?int $limit;
    private ?string $startDate;
    private ?string $endDate;

    public function __construct(
        string $stripeAccountId,
        ?int $limit = null,
        ?string $startDate = null,
        ?string $endDate = null
    ) {
        $this->stripeAccountId = $stripeAccountId;
        $this->limit = $limit;
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

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }
}
