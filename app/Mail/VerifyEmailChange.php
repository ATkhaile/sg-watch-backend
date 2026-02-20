<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailChange extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private array $data)
    {
    }

    public function build()
    {
        return $this->view('mails.verify-email-change')
            ->subject('メールアドレス変更の確認')
            ->with([
                'name' => $this->data['name'],
                'newEmail' => $this->data['new_email'],
                'verificationUrl' => $this->data['verification_url'],
            ]);
    }
}
