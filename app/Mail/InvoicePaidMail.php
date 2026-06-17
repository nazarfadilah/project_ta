<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\PeminjamanTransaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $peminjaman;

    public function __construct(Invoice $invoice, PeminjamanTransaksi $peminjaman)
    {
        $this->invoice = $invoice;
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        return $this->subject('Pembayaran Invoice Lunas - SIPRASA - ' . $this->invoice->noInvoice)
                    ->view('emails.invoice_paid');
    }
}
