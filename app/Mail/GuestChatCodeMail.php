<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GuestChatCodeMail extends Mailable
{
    public $code;
    public $name;

    public function __construct($code, $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('株式会社Game】 個別相談チャット再開リンク')
            ->view('emails.guest_chat_code')
            ->with([
                'code' => $this->code,
                'name' => $this->name,
            ]);
    }
}
