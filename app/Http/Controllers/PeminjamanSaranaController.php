<?php
namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Sarana;
use App\Models\DetailPeminjamanSarana;
use Illuminate\Http\Request;

class PeminjamanSaranaController extends Controller
{
    /**
     * Display a listing of peminjaman sarana
     */
    public function index()
    {
        $peminjamanSaranas = PeminjamanTransaksi::with('detailSaranas', 'guest')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('main.peminjaman_sarana.index', compact('peminjamanSaranas'));
    }

    /**
     * Show the form for creating a new peminjaman
     */
    public function create()
    {
        $saranas = Sarana::all();
        $mode = 'create';

        return view('main.peminjaman_sarana.form', compact('saranas', 'mode'));
    }

    /**
     * Store a newly created peminjaman in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'durasi' => 'required|integer|min:1',
            'sarana_id' => 'required|exists:sarana,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $saranaModel = Sarana::findOrFail($validated['sarana_id']);
        if ($validated['jumlah'] > $saranaModel->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah pinjam melebihi stok yang tersedia (' . $saranaModel->stok . ' unit).'])->withInput();
        }

        // Create peminjaman transaksi
        $peminjaman = PeminjamanTransaksi::create([
            'kodePeminjaman' => 'SARANA-' . date('YmdHis'),
            'guestId' => 1, // Default guest ID, adjust as needed
            'facilityId' => 1, // Default facility ID
            'tanggal' => $validated['tanggal'],
            'jamMulai' => now(),
            'durasi' => $validated['durasi'],
            'statusPeminjaman' => 'RESERVASI',
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // Create detail peminjaman sarana
        DetailPeminjamanSarana::create([
            'sarana_id' => $validated['sarana_id'],
            'peminjaman_id' => $peminjaman->id,
            'jumlah' => $validated['jumlah'],
        ]);

        return redirect()->route('main.peminjaman_sarana.index')
            ->with('success', 'Peminjaman sarana berhasil dibuat');
    }

    /**
     * Show the form for editing the specified peminjaman
     */
    public function edit($id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        $saranas = Sarana::all();
        $mode = 'edit';

        return view('main.peminjaman_sarana.form', compact('peminjaman', 'saranas', 'mode'));
    }

    /**
     * Update the specified peminjaman in storage
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'durasi' => 'required|integer|min:1',
            'sarana_id' => 'required|exists:sarana,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $saranaModel = Sarana::findOrFail($validated['sarana_id']);
        if ($validated['jumlah'] > $saranaModel->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah pinjam melebihi stok yang tersedia (' . $saranaModel->stok . ' unit).'])->withInput();
        }

        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        
        $peminjaman->update([
            'tanggal' => $validated['tanggal'],
            'durasi' => $validated['durasi'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // Update detail peminjaman sarana
        $peminjaman->detailSaranas()->delete();
        DetailPeminjamanSarana::create([
            'sarana_id' => $validated['sarana_id'],
            'peminjaman_id' => $peminjaman->id,
            'jumlah' => $validated['jumlah'],
        ]);

        return redirect()->route('main.peminjaman_sarana.index')
            ->with('success', 'Peminjaman sarana berhasil diupdate');
    }

    /**
     * Remove the specified peminjaman from storage
     */
    public function destroy($id)
    {
        $peminjaman = PeminjamanTransaksi::findOrFail($id);
        $peminjaman->detailSaranas()->delete();
        $peminjaman->delete();

        return redirect()->route('main.peminjaman_sarana.index')
            ->with('success', 'Peminjaman sarana berhasil dihapus');
    }
}
