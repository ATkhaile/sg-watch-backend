<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationCode extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $mail = $this->subject('【お支払い完了】アクティベーションコードのお知らせ')
            ->view('mails.activationCode')
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