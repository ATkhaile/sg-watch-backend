<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompleteMemberRegister extends Mailable
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
        return $this->subject('【新規会員登録完了】')
            ->view('mails.complete_member_register')
            ->with([
                'data' => $this->data,
            ]);
    }
}
