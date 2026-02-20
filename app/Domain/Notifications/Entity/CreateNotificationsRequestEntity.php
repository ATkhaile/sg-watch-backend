<?php

namespace App\Domain\Notifications\Entity;

use Illuminate\Http\UploadedFile;

class CreateNotificationsRequestEntity
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly ?string $push_type = null,
        public readonly ?string $push_datetime = null,
        public readonly ?bool $push_now_flag = null,
        public readonly ?UploadedFile $file = null,
        public readonly ?string $sender_type = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'push_type' => $this->push_type,
            'push_datetime' => $this->push_datetime,
            'push_now_flag' => $this->push_now_flag,
            'sender_type' => $this->sender_type,
        ];
    }
}
