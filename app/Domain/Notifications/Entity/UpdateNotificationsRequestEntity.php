<?php

namespace App\Domain\Notifications\Entity;

use Illuminate\Http\UploadedFile;

class UpdateNotificationsRequestEntity
{
    public function __construct(
        private ?string $title = null,
        private ?string $content = null,
        private ?string $push_type = null,
        private ?string $push_datetime = null,
        private ?bool $push_now_flag = null,
        private ?UploadedFile $file = null,
        private ?string $sender_type = null
    ) {
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

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }
    public function getSenderType(): ?string
    {
        return $this->sender_type;
    }
}
