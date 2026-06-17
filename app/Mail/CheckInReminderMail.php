<?php

namespace App\Mail;

use App\Models\PeminjamanTransaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckInReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct(PeminjamanTransaksi $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        return $this->subject('Pengingat Check-In Reservasi SIPRASA - ' . $this->peminjaman->kodePeminjaman)
                    ->view('emails.checkin_reminder');
    }
}
