<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterUser extends Mailable
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
        $mail = $this->subject('【ご登録完了】ご登録完了のお知らせ')
            ->view('mails.successRegister')
            ->with([
                'data' => $this->data,
            ]);

        // BCCを追加（.envのMAIL_BCCが設定されている場合）
        $bcc = env('MAIL_BCC');
        if ($bcc) {
            $mail->bcc($bcc);
        }
        
        return $mail;
    }
}
