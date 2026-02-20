<?php

namespace App\Domain\Notifications\Entity;

class NotificationDetailEntity
{
    public function __construct(
        private ?int $id = null,
        private ?string $title = null,
        private ?string $content = null,
        private ?string $push_type = null,
        private ?string $sender_type = null,
        private ?string $push_datetime = null,
        private ?bool $push_now_flag = null,
        private ?string $created_at  = null,
        private ?string $updated_at = null,
        private ?string $create_user_name = null,
        private ?string $image_url = null,
        private int $statusCode = 0
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->title !== null) {
            $result['title'] = $this->title;
        }

        if ($this->content !== null) {
            $result['content'] = $this->content;
        }

        if ($this->push_type !== null) {
            $result['push_type'] = $this->push_type;
        }

        if ($this->sender_type !== null) {
            $result['sender_type'] = $this->sender_type;
        }

        if ($this->push_datetime !== null) {
            $result['push_datetime'] = $this->push_datetime;
        }

        if ($this->push_now_flag !== null) {
            $result['push_now_flag'] = $this->push_now_flag;
        }

        if ($this->created_at !== null) {
            $result['created_at'] = $this->created_at;
        }

        if ($this->updated_at !== null) {
            $result['updated_at'] = $this->updated_at;
        }
        if ($this->create_user_name !== null) {
            $result['create_user_name'] = $this->create_user_name;
        }
        if ($this->image_url !== null) {
            $result['image_url'] = $this->image_url;
        }

        return $result;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getPushType(): ?string
    {
        return $this->push_type;
    }

    public function getPushDatetime(): ?string
    {
        return $this->push_datetime;
    }

    public function getPushNowFlag(): ?bool
    {
        return $this->push_now_flag;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setPushType(?string $push_type): self
    {
        $this->push_type = $push_type;
        return $this;
    }

    public function setPushDatetime(?string $push_datetime): self
    {
        $this->push_datetime = $push_datetime;
        return $this;
    }

    public function setPushNowFlag(?bool $push_now_flag): self
    {
        $this->push_now_flag = $push_now_flag;
        return $this;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->created_at = $createdAt;
        return $this;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    public function getSenderType(): ?string
    {
        return $this->sender_type;
    }

    public function setSenderType(?string $sender_type): self
    {
        $this->sender_type = $sender_type;
        return $this;
    }
}
