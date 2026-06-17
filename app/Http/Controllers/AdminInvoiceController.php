<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PeminjamanTransaksi;
use App\Models\User;
use App\Mail\InvoicePaidMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AdminInvoiceController extends Controller
{
    /**
     * Show invoice detail for admin
     */
    public function show($peminjaman_id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($peminjaman_id);
        $peminjaman->load('guest', 'paketRuangan.ruangan.gedung', 'user');

        // Get invoice
        $invoice = Invoice::where('peminjamanId', $peminjaman_id)->firstOrFail();

        return view('main.transaksi.invoice.index', compact('invoice', 'peminjaman'));
    }

    /**
     * Update invoice payment status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'statusInvoice' => 'required|in:UNPAID,PAID',
        ]);

        $invoice = Invoice::findOrFail($id);
        
        $statusInvoice = $validated['statusInvoice'];
        $status_pembayaran = ($statusInvoice === 'PAID') ? 'LUNAS' : 'BELUM_BAYAR';
        $tglPaid = ($statusInvoice === 'PAID') ? now() : null;

        $invoice->update([
            'statusInvoice' => $statusInvoice,
            'status_pembayaran' => $status_pembayaran,
            'tglPaid' => $tglPaid,
            'tgl_pembayaran' => $tglPaid,
        ]);

        // Kirim email jika statusInvoice diubah menjadi PAID (Lunas)
        if ($statusInvoice === 'PAID') {
            $peminjaman = $invoice->peminjamanTransaksi;
            if ($peminjaman) {
                $user = $peminjaman->user ?? User::where('guestId', $peminjaman->guestId)->first();
                if ($user && $user->email) {
                    try {
                        Mail::to($user->email)->send(new InvoicePaidMail($invoice, $peminjaman));
                    } catch (\Exception $e) {
                        \Log::error('Gagal kirim email invoice paid: ' . $e->getMessage());
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran invoice berhasil diperbarui.');
    }
}
