<?php

namespace App\Http\Controllers;

use App\Models\PaketRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PaketRuanganController extends Controller
{
        // Code Lama:
        // $paketRuangans = PaketRuangan::with('ruangan.gedung')->orderBy('id', 'desc')->get();
        // Code Baru:
        $paketRuangans = PaketRuangan::with('ruangan')->orderBy('id', 'desc')->get();
        return view('main.master.paket_ruangan.index', compact('paketRuangans'));

    public function create()
    {
        $paketRuangan = new PaketRuangan();
        // Code Lama:
        // $ruangans = Ruangan::with('gedung')->orderBy('nama_ruangan')->get();
        // Code Baru:
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        $mode = 'create';
        return view('main.master.paket_ruangan.form', compact('paketRuangan', 'ruangans', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id_ruangan',
            'nama_paket' => 'required|string|max:255',
            'durasi' => 'nullable|integer|min:1|max:999',
            'harga' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|in:ACTIVE,INACTIVE,MAINTENANCE',
        ]);

        $validated['currency'] = 'IDR';

        PaketRuangan::create($validated);

        return redirect()->route('main.paket_ruangan.index')->with('success', 'Paket Ruangan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $paketRuangan = PaketRuangan::findOrFail($id);
        return redirect()->route('main.paket_ruangan.edit', $id);
    }

    public function edit($id)
    {
        $paketRuangan = PaketRuangan::findOrFail($id);
        // Code Lama:
        // $ruangans = Ruangan::with('gedung')->orderBy('nama_ruangan')->get();
        // Code Baru:
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        $mode = 'edit';
        return view('main.master.paket_ruangan.form', compact('paketRuangan', 'ruangans', 'mode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id_ruangan',
            'nama_paket' => 'required|string|max:255',
            'durasi' => 'nullable|integer|min:1|max:999',
            'harga' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|in:ACTIVE,INACTIVE,MAINTENANCE',
        ]);

        $paketRuangan = PaketRuangan::findOrFail($id);
        $paketRuangan->update($validated);
        
        return redirect()->route('main.paket_ruangan.index')->with('success', 'Paket Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $paketRuangan = PaketRuangan::findOrFail($id);
        $paketRuangan->delete();
        
        return redirect()->route('main.paket_ruangan.index')->with('success', 'Paket Ruangan berhasil dihapus.');
    }
}