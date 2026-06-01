<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $gedungs = Gedung::orderBy('id_gedung', 'desc')->get();
        return view('main.master.gedung.index', compact('gedungs'));
    }

    public function create()
    {
        $gedung = new Gedung();
        $mode = 'create';
        return view('main.master.gedung.form', compact('gedung', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'koordinat' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        Gedung::create($validated);

        return redirect()->route('main.gedung.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function show($id)
    {
        $gedung = Gedung::findOrFail($id);
        return redirect()->route('main.gedung.edit', $id);
    }

    public function edit($id)
    {
        $gedung = Gedung::findOrFail($id);
        $mode = 'edit';
        return view('main.master.gedung.form', compact('gedung', 'mode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'koordinat' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $gedung = Gedung::findOrFail($id);
        $gedung->update($validated);
        
        return redirect()->route('main.gedung.index')->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gedung = Gedung::findOrFail($id);
        $gedung->delete();
        
        return redirect()->route('main.gedung.index')->with('success', 'Gedung berhasil dihapus.');
    }
}