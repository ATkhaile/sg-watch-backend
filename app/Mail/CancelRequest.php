<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelRequest extends Mailable
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
        return $this->subject('解約申請時の確認メール')
            ->view('mails.cancelRequest')
            ->with([
                'data' => $this->data,
            ]);
    }
}
