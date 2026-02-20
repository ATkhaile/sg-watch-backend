<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaunaCancelBooking extends Mailable
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
        return $this->subject("【" . $this->data['shop_info']->name . "】予約のキャンセルが完了しました。")
            ->view('mails.sauna_cancel_booking')
            ->with([
                'data' => $this->data,
            ]);
    }
}
