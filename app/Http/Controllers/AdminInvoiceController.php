<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PeminjamanTransaksi;
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
}
