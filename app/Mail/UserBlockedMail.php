<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserBlockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $unblockLink;

    public function __construct($user, $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->unblockLink = route('register.unblock', ['email' => $user->email]);
    }

    public function build()
    {
        return $this->subject('Pemberitahuan Akun Ditangguhkan (Di Blokir) - SIPRASA')
                    ->view('emails.user_blocked');
    }
}
