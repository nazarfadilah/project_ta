<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminPeminjamanTransaksiController extends Controller
{
    /**
     * Display a listing of peminjaman/reservasi
     */
    public function index()
    {
        $peminjaman = PeminjamanTransaksi::with('guest', 'paketRuangan.ruangan.gedung', 'user')
            ->orderBy('createdAt', 'DESC')
            ->get();

        return view('main.transaksi.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show detail form with approve/reject buttons
     */
    public function show($id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        $peminjaman->load('guest', 'paketRuangan.ruangan.gedung', 'user', 'invoice', 'detailSaranas.sarana');

        // Check if invoice exists
        $invoice = Invoice::where('peminjamanId', $id)->first();

        return view('main.transaksi.peminjaman.form', compact('peminjaman', 'invoice'));
    }

    /**
     * Approve peminjaman (POST request)
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'catatanApproval' => 'nullable|string|max:1000',
        ]);

        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        
        // Update approval status
        $peminjaman->update([
            'statusApproval' => 'APPROVED',
            'catatanApproval' => $validated['catatanApproval'] ?? null,
            'tanggalApproval' => Carbon::now(),
        ]);

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Peminjaman telah disetujui.');
    }

    /**
     * Reject peminjaman (POST request)
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'catatanApproval' => 'required|string|max:1000',
        ]);

        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        
        // Update rejection status
        $peminjaman->update([
            'statusApproval' => 'REJECTED',
            'catatanApproval' => $validated['catatanApproval'],
            'tanggalApproval' => Carbon::now(),
        ]);

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Peminjaman telah ditolak.');
    }

    /**
     * Check-in peminjaman (POST request)
     */
    public function checkIn($id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($id);

        if ($peminjaman->statusApproval !== 'APPROVED') {
            return redirect()->back()->with('error', 'Check-in hanya dapat dilakukan untuk peminjaman yang disetujui.');
        }

        $peminjaman->update([
            'statusPeminjaman' => 'CHECK_IN',
            'checkIn' => Carbon::now(),
        ]);

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Check-in berhasil diproses. Selamat menggunakan ruangan!');
    }

    /**
     * Check-out peminjaman (POST request)
     */
    public function checkOut(Request $request, $id)
    {
        $validated = $request->validate([
            'kondisiReturn' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_BERAT,HILANG',
            'catatanKerusakan' => 'nullable|string|max:1000',
            'estimasiDamage' => 'nullable|numeric|min:0',
            'biayaTambahan' => 'nullable|numeric|min:0',
        ]);

        $peminjaman = PeminjamanTransaksi::findOrFail($id);

        if ($peminjaman->statusPeminjaman !== 'CHECK_IN') {
            return redirect()->back()->with('error', 'Check-out hanya dapat dilakukan jika status transaksi sedang CHECK-IN.');
        }

        $peminjaman->update([
            'statusPeminjaman' => 'SELESAI',
            'checkOut' => Carbon::now(),
            'kondisiReturn' => $validated['kondisiReturn'],
            'catatanKerusakan' => $validated['catatanKerusakan'] ?? null,
            'estimasiDamage' => $validated['estimasiDamage'] ?? 0.00,
            'biayaTambahan' => $validated['biayaTambahan'] ?? 0.00,
        ]);

        return redirect()->route('main.transaksi.peminjaman.show', $id)
            ->with('success', 'Check-out berhasil diproses. Peminjaman telah selesai.');
    }
}
