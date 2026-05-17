<?php

namespace App\Http\Controllers;

use App\Models\Tentang;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * Display a listing of Privacy data
     */
    public function index()
    {
        $privacy = Tentang::where('key', 'kebijakan_privasi')->orderBy('key2')->get();
        return view('main.landingPage.privacy.index', ['privacy' => $privacy]);
    }

    /**
     * Show the form for creating a new Privacy
     */
    public function create()
    {
        return view('main.landingPage.privacy.form', ['privacy' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created Privacy in storage
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

        $validated['key'] = 'kebijakan_privasi';
        Tentang::create($validated);

        return redirect()->route('main.landing.privacy.index')
                       ->with('success', 'Kebijakan & Privasi berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified Privacy
     */
    public function edit($id)
    {
        $privacy = Tentang::find($id);
        if (!$privacy || $privacy->key !== 'kebijakan_privasi') {
            return redirect()->route('main.landing.privacy.index')
                           ->with('error', 'Data tidak ditemukan');
        }
        return view('main.landingPage.privacy.form', ['privacy' => $privacy, 'mode' => 'edit']);
    }

    /**
     * Update the specified Privacy in storage
     */
    public function update(Request $request, $id)
    {
        $privacy = Tentang::find($id);
        if (!$privacy || $privacy->key !== 'kebijakan_privasi') {
            return redirect()->route('main.landing.privacy.index')
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

        $privacy->update($validated);

        return redirect()->route('main.landing.privacy.index')
                       ->with('success', 'Kebijakan & Privasi berhasil diperbarui');
    }

    /**
     * Remove the specified Privacy from storage
     */
    public function destroy($id)
    {
        $privacy = Tentang::find($id);
        if (!$privacy || $privacy->key !== 'kebijakan_privasi') {
            return redirect()->route('main.landing.privacy.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $privacy->delete();

        return redirect()->route('main.landing.privacy.index')
                       ->with('success', 'Kebijakan & Privasi berhasil dihapus');
    }
}
