<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationComplete extends Mailable
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
        $mail = $this->subject('【アクティベート完了】ご利用開始のお知らせ')
            ->view('mails.activationComplete')
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
