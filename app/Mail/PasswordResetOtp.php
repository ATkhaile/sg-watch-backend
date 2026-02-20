<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtp extends Mailable
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
        return $this->view('mails.password-reset-otp')
            ->subject(__('mail.passwordResetOtp.title'))
            ->with([
                'code' => $this->code,
                'name' => $this->name,
            ]);
    }
}
