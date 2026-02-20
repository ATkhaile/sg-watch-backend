<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageAdminNotification extends Mailable
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
            subject: "【要対応】新しいお問い合わせ（{$ticketNumber}）",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.contact-message-admin-notification',
            with: [
                'data' => $this->data,
                'admin_url' => config('app.url') . '/admin/contacts',
            ],
        );
    }
}
