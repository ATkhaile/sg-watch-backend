<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMember extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $data;
    /**
     * Create a new message instance.
     */

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('お問い合わせありがとうございます【自動返信】')
            ->view('mails.contact_mail')
            ->with([
                'data' => $this->data,
            ]);
    }
}
