<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PeminjamanTransaksi;
use Illuminate\Http\Request;

class UsersInvoiceController extends Controller
{
    /**
     * Menampilkan invoice berdasarkan peminjaman/reservasi
     */
    public function index($peminjaman_id)
    {
        // Cek apakah peminjaman milik user yang login
        $peminjaman = PeminjamanTransaksi::findOrFail($peminjaman_id);
        
        $user = auth()->user();
        
        // Cek hak akses invoice menggunakan guestId peminjam
        if ($peminjaman->guestId != $user->guestId) {
            return redirect()->route('users.main.reservasi.index')
                ->with('error', 'Anda tidak memiliki akses ke invoice ini.');
        }

        // Ambil invoice berdasarkan peminjaman
        $invoice = Invoice::where('peminjamanId', $peminjaman_id)->firstOrFail();

        // Eager load relasi
        $invoice->load('peminjamanTransaksi.paketRuangan.ruangan.gedung', 
                       'peminjamanTransaksi.guest');

        return view('users.main.invoice.index', compact('invoice', 'peminjaman'));
    }
}
