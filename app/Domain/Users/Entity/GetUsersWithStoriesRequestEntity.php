<?php

namespace App\Domain\Users\Entity;

class GetUsersWithStoriesRequestEntity
{
    private ?int $page;
    private ?int $limit;

    public function __construct(
        ?int $page = null,
        ?int $limit = null
    ) {
        $this->page = $page;
        $this->limit = $limit;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
