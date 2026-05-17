<?php

namespace App\Http\Controllers;

use App\Models\Tentang;
use Illuminate\Http\Request;

class TentangController extends Controller
{
    /**
     * Display a listing of Tentang data
     */
    public function index()
    {
        $tentang = Tentang::whereNull('key2')->orWhere('key2', '')->orderBy('key')->get();
        return view('main.landingPage.tentang.index', ['tentang' => $tentang]);
    }

    /**
     * Show the form for creating a new Tentang
     */
    public function create()
    {
        return view('main.landingPage.tentang.form', ['tentang' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created Tentang in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:tentang,key',
            'value' => 'required|string',
        ], [
            'key.required' => 'Field key harus diisi',
            'key.unique' => 'Key sudah ada',
            'value.required' => 'Field value harus diisi',
        ]);

        Tentang::create($validated);

        return redirect()->route('main.landing.tentang.index')
                       ->with('success', 'Data tentang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified Tentang
     */
    public function edit($id)
    {
        $tentang = Tentang::find($id);
        if (!$tentang) {
            return redirect()->route('main.landing.tentang.index')
                           ->with('error', 'Data tidak ditemukan');
        }
        return view('main.landingPage.tentang.form', ['tentang' => $tentang, 'mode' => 'edit']);
    }

    /**
     * Update the specified Tentang in storage
     */
    public function update(Request $request, $id)
    {
        $tentang = Tentang::find($id);
        if (!$tentang) {
            return redirect()->route('main.landing.tentang.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $validated = $request->validate([
            'key' => 'required|string|unique:tentang,key,' . $id . ',id',
            'value' => 'required|string',
        ], [
            'key.required' => 'Field key harus diisi',
            'key.unique' => 'Key sudah ada',
            'value.required' => 'Field value harus diisi',
        ]);

        $tentang->update($validated);

        return redirect()->route('main.landing.tentang.index')
                       ->with('success', 'Data tentang berhasil diperbarui');
    }

    /**
     * Remove the specified Tentang from storage
     */
    public function destroy($id)
    {
        $tentang = Tentang::find($id);
        if (!$tentang) {
            return redirect()->route('main.landing.tentang.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $tentang->delete();

        return redirect()->route('main.landing.tentang.index')
                       ->with('success', 'Data tentang berhasil dihapus');
    }
}
