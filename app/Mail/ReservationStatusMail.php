<?php

namespace App\Mail;

use App\Models\PeminjamanTransaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $statusType; // 'APPROVED', 'REJECTED', 'UPDATED'

    public function __construct(PeminjamanTransaksi $peminjaman, $statusType)
    {
        $this->peminjaman = $peminjaman;
        $this->statusType = $statusType;
    }

    public function build()
    {
        $subject = 'Notifikasi Reservasi SIPRASA - ' . $this->peminjaman->kodePeminjaman;
        if ($this->statusType === 'APPROVED') {
            $subject .= ' (DISETUJUI)';
        } elseif ($this->statusType === 'REJECTED') {
            $subject .= ' (DITOLAK)';
        } elseif ($this->statusType === 'PENDING') {
            $subject .= ' (BERHASIL DIAJUKAN)';
        } else {
            $subject .= ' (DIPERBARUI)';
        }

        return $this->subject($subject)
                    ->view('emails.reservation_status');
    }
}
