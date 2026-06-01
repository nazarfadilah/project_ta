<?php

namespace App\Http\Controllers;

use App\Models\Sarana;
use Illuminate\Http\Request;

class SaranaController extends Controller
{
    public function index()
    {
        $saranas = Sarana::orderBy('id', 'desc')->get();
        return view('main.master.sarana.index', compact('saranas'));
    }

    public function create()
    {
        $sarana = new Sarana();
        $mode = 'create';
        return view('main.master.sarana.form', compact('sarana', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sarana' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_BERAT,HILANG',
            'keterangan' => 'nullable|string',
        ]);

        Sarana::create($validated);

        return redirect()->route('main.sarana.index')->with('success', 'Sarana berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sarana = Sarana::findOrFail($id);
        return redirect()->route('main.sarana.edit', $id);
    }

    public function edit($id)
    {
        $sarana = Sarana::findOrFail($id);
        $mode = 'edit';
        return view('main.master.sarana.form', compact('sarana', 'mode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sarana' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|in:BAIK,RUSAK_RINGAN,RUSAK_BERAT,HILANG',
            'keterangan' => 'nullable|string',
        ]);

        $sarana = Sarana::findOrFail($id);
        $sarana->update($validated);
        
        return redirect()->route('main.sarana.index')->with('success', 'Sarana berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sarana = Sarana::findOrFail($id);
        $sarana->delete();
        
        return redirect()->route('main.sarana.index')->with('success', 'Sarana berhasil dihapus.');
    }
}