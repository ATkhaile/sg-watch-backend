<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public array $mailData;
    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

    public function build(): self
    {
        Log::info('Building notification email', [
            'title' => $this->mailData['title'],
            'mail_from' => $this->mailData['mail_from'],
            'from' => $this->from
        ]);
        return $this->from($this->mailData['mail_from'], config('mail.from.name'))
            ->subject($this->mailData['title'])
            ->html($this->mailData['content']);
    }
}
