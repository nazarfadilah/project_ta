<?php

namespace App\Http\Controllers;

use App\Models\Tentang;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    /**
     * Display a listing of Terms data
     */
    public function index()
    {
        $terms = Tentang::where('key', 'term&conditions')->orderBy('key2')->get();
        return view('main.landingPage.terms.index', ['terms' => $terms]);
    }

    /**
     * Show the form for creating a new Terms
     */
    public function create()
    {
        return view('main.landingPage.terms.form', ['terms' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created Terms in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key2' => 'required|string|unique:tentang,key2',
            'value' => 'required|string',
        ], [
            'key2.required' => 'Judul harus diisi',
            'key2.unique' => 'Judul sudah ada',
            'value.required' => 'Isi harus diisi',
        ]);

        $validated['key'] = 'term&conditions';
        Tentang::create($validated);

        return redirect()->route('main.landing.terms.index')
                       ->with('success', 'Syarat & Ketentuan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified Terms
     */
    public function edit($id)
    {
        $terms = Tentang::find($id);
        if (!$terms || $terms->key !== 'term&conditions') {
            return redirect()->route('main.landing.terms.index')
                           ->with('error', 'Data tidak ditemukan');
        }
        return view('main.landingPage.terms.form', ['terms' => $terms, 'mode' => 'edit']);
    }

    /**
     * Update the specified Terms in storage
     */
    public function update(Request $request, $id)
    {
        $terms = Tentang::find($id);
        if (!$terms || $terms->key !== 'term&conditions') {
            return redirect()->route('main.landing.terms.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $validated = $request->validate([
            'key2' => 'required|string|unique:tentang,key2,' . $id . ',id',
            'value' => 'required|string',
        ], [
            'key2.required' => 'Judul harus diisi',
            'key2.unique' => 'Judul sudah ada',
            'value.required' => 'Isi harus diisi',
        ]);

        $terms->update($validated);

        return redirect()->route('main.landing.terms.index')
                       ->with('success', 'Syarat & Ketentuan berhasil diperbarui');
    }

    /**
     * Remove the specified Terms from storage
     */
    public function destroy($id)
    {
        $terms = Tentang::find($id);
        if (!$terms || $terms->key !== 'term&conditions') {
            return redirect()->route('main.landing.terms.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $terms->delete();

        return redirect()->route('main.landing.terms.index')
                       ->with('success', 'Syarat & Ketentuan berhasil dihapus');
    }
}
