<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyRegistration extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private array $data)
    {
    }

    public function build()
    {
        return $this->view('mails.verify-registration')
            ->subject(__('mail.verifyRegistration.title'))
            ->with([
                'name' => $this->data['name'],
                'verificationUrl' => $this->data['verificationUrl']
            ]);
    }
}
