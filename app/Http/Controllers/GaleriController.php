<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Display a listing of Galeri data
     */
    public function index()
    {
        $galeri = Galeri::orderBy('created_at', 'desc')->get();
        return view('main.landingPage.galeri.index', ['galeri' => $galeri]);
    }

    /**
     * Show the form for creating a new Galeri
     */
    public function create()
    {
        return view('main.landingPage.galeri.form', ['galeri' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created Galeri in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string',
            'judul' => 'required|string',
            'isi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'kategori.required' => 'Kategori harus diisi',
            'judul.required' => 'Judul harus diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $path = $file->store('galeri', 'public');
            $validated['gambar'] = $path;
        }

        $validated['created_at'] = now();
        Galeri::create($validated);

        return redirect()->route('main.landing.galeri.index')
                       ->with('success', 'Galeri berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified Galeri
     */
    public function edit($id)
    {
        $galeri = Galeri::find($id);
        if (!$galeri) {
            return redirect()->route('main.landing.galeri.index')
                           ->with('error', 'Data tidak ditemukan');
        }
        return view('main.landingPage.galeri.form', ['galeri' => $galeri, 'mode' => 'edit']);
    }

    /**
     * Update the specified Galeri in storage
     */
    public function update(Request $request, $id)
    {
        $galeri = Galeri::find($id);
        if (!$galeri) {
            return redirect()->route('main.landing.galeri.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $validated = $request->validate([
            'kategori' => 'required|string',
            'judul' => 'required|string',
            'isi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'kategori.required' => 'Kategori harus diisi',
            'judul.required' => 'Judul harus diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $file = $request->file('gambar');
            $path = $file->store('galeri', 'public');
            $validated['gambar'] = $path;
        }

        $galeri->update($validated);

        return redirect()->route('main.landing.galeri.index')
                       ->with('success', 'Galeri berhasil diperbarui');
    }

    /**
     * Remove the specified Galeri from storage
     */
    public function destroy($id)
    {
        $galeri = Galeri::find($id);
        if (!$galeri) {
            return redirect()->route('main.landing.galeri.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        // Delete image file
        if ($galeri->gambar && Storage::disk('public')->exists($galeri->gambar)) {
            Storage::disk('public')->delete($galeri->gambar);
        }

        $galeri->delete();

        return redirect()->route('main.landing.galeri.index')
                       ->with('success', 'Galeri berhasil dihapus');
    }
}
