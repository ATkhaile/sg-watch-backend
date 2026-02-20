<?php

namespace App\Domain\Comment\Entity;

class CreateCommentRequestEntity
{
    private string $model;
    private int $modelId;
    private int $userId;
    private string $content;

    public function __construct(
        string $model,
        int $modelId,
        int $userId,
        string $content
    ) {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->userId = $userId;
        $this->content = $content;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getModelId(): int
    {
        return $this->modelId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
