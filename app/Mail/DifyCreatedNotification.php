<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DifyCreatedNotification extends Mailable
{
    use Queueable;
    use SerializesModels;
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('サービスが正常に認証されました')
                    ->view('emails.dify_created_notification')
                    ->with([
                        'user' => $this->user,
                    ]);
    }
}
