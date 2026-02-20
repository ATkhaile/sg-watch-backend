<?php

namespace App\Domain\Chat\Entity;

class ChatMessage implements \JsonSerializable
{
    private ?int $id;
    private ?int $user_id;
    private ?int $receiver_id;
    private ?string $message;
    private ?string $message_type;
    private ?string $file_url;
    private ?string $file_name;
    private ?string $file_type;
    private ?int $file_size;
    private ?bool $is_read;
    private ?string $read_at;
    private ?string $created_at;
    private ?string $updated_at;
    private int $statusCode;

    public function __construct(
        ?int $id = null,
        ?int $user_id = null,
        ?int $receiver_id = null,
        ?string $message = null,
        ?string $message_type = null,
        ?string $file_url = null,
        ?string $file_name = null,
        ?string $file_type = null,
        ?int $file_size = null,
        ?bool $is_read = null,
        ?string $read_at = null,
        ?string $created_at = null,
        ?string $updated_at = null,
        int $statusCode = 200
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->receiver_id = $receiver_id;
        $this->message = $message;
        $this->message_type = $message_type;
        $this->file_url = $file_url;
        $this->file_name = $file_name;
        $this->file_type = $file_type;
        $this->file_size = $file_size;
        $this->is_read = $is_read;
        $this->read_at = $read_at;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->statusCode = $statusCode;
    }

    public function jsonSerialize(): array
    {
        $result = [];
        if ($this->id !== null) {
            $result['id'] = $this->id;
        }
        if ($this->user_id !== null) {
            $result['user_id'] = $this->user_id;
        }
        if ($this->receiver_id !== null) {
            $result['receiver_id'] = $this->receiver_id;
        }
        if ($this->message !== null) {
            $result['message'] = $this->message;
        }
        if ($this->created_at !== null) {
            $result['created_at'] = $this->created_at;
        }
        if ($this->updated_at !== null) {
            $result['updated_at'] = $this->updated_at;
        }
        return $result;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['user_id'] ?? null,
            $data['receiver_id'] ?? null,
            $data['message'] ?? null,
            $data['message_type'] ?? null,
            $data['file_url'] ?? null,
            $data['file_name'] ?? null,
            $data['file_type'] ?? null,
            $data['file_size'] ?? null,
            $data['is_read'] ?? null,
            $data['read_at'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null,
            $data['status_code'] ?? 200
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUserId(): ?int
    {
        return $this->user_id;
    }
    public function getReceiverId(): ?int
    {
        return $this->receiver_id;
    }
    public function getMessage(): ?string
    {
        return $this->message;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
    public function getMessageType(): ?string
    {
        return $this->message_type;
    }
    public function getFileUrl(): ?string
    {
        return $this->file_url;
    }
    public function getFileName(): ?string
    {
        return $this->file_name;
    }
    public function getFileType(): ?string
    {
        return $this->file_type;
    }
    public function getFileSize(): ?int
    {
        return $this->file_size;
    }
    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }
    public function getReadAt(): ?string
    {
        return $this->read_at;
    }
    public function getStatus(): int
    {
        return $this->statusCode;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function setReceiverId(?int $receiver_id): self
    {
        $this->receiver_id = $receiver_id;
        return $this;
    }
    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }
    public function setCreatedAt(?string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function setUpdatedAt(?string $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    public function setStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
