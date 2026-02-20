<?php

namespace App\Mail;

use App\Models\MailSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        $ticketNumber = $this->data['ticket_number'] ?? '';

        return new Envelope(
            subject: "【TERAKONA】お問い合わせを受け付けました（{$ticketNumber}）",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.contact-message-received',
            with: [
                'data' => $this->data,
            ],
        );
    }

    public function build(): self
    {
        // BCCを追加（設定がある場合）
        $bcc = MailSetting::getGlobalSetting('bcc_email');
        if ($bcc) {
            $this->bcc($bcc);
        }

        return $this;
    }
}
