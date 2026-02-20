<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Domain\MySns\Entity\MySnsDetailEntity;

class MySnsCreatedNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public MySnsDetailEntity $mySns;
    public $user;

    public function __construct(MySnsDetailEntity $mySns, $user)
    {
        $this->mySns = $mySns;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('登録成功通知')
                    ->view('emails.mysns_created_notification')
                    ->with([
                        'mySns' => $this->mySns,
                        'user' => $this->user,
                    ]);
    }
}
