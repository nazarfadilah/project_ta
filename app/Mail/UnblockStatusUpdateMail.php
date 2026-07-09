<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnblockStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;

    public function __construct($user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    public function build()
    {
        $statusText = $this->status === 'APPROVED' ? 'Disetujui' : 'Ditolak';
        return $this->subject("Hasil Pengajuan Buka Blokir Akun SIPRASA - {$statusText}")
                    ->view('emails.unblock_status_update');
    }
}
