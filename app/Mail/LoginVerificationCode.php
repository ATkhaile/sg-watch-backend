<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginVerificationCode extends Mailable
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
        return $this->view('mails.login-verification-code')
            ->subject(__('mail.loginVerificationCode.title'))
            ->with([
                'code' => $this->code,
                'name' => $this->name
            ]);
    }
}
