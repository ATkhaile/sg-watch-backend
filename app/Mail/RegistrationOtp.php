<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationOtp extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private string $code,
        private string $name
    ) {
    }

    public function build()
    {
        return $this->view('mails.registration-otp')
            ->subject(__('mail.registrationOtp.title'))
            ->with([
                'code' => $this->code,
                'name' => $this->name,
            ]);
    }
}
