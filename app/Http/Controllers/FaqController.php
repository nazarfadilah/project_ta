<?php

namespace App\Http\Controllers;

use App\Models\Tentang;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQ data
     */
    public function index()
    {
        $faq = Tentang::where('key', 'faq')->orderBy('key2')->get();
        return view('main.landingPage.faq.index', ['faq' => $faq]);
    }

    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        return view('main.landingPage.faq.form', ['faq' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created FAQ in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key2' => 'required|string|unique:tentang,key2',
            'value' => 'required|string',
        ], [
            'key2.required' => 'Judul harus diisi',
            'key2.unique' => 'Judul sudah ada',
            'value.required' => 'Jawaban harus diisi',
        ]);

        $validated['key'] = 'faq';
        Tentang::create($validated);

        return redirect()->route('main.landing.faq.index')
                       ->with('success', 'FAQ berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit($id)
    {
        $faq = Tentang::find($id);
        if (!$faq || $faq->key !== 'faq') {
            return redirect()->route('main.landing.faq.index')
                           ->with('error', 'Data tidak ditemukan');
        }
        return view('main.landingPage.faq.form', ['faq' => $faq, 'mode' => 'edit']);
    }

    /**
     * Update the specified FAQ in storage
     */
    public function update(Request $request, $id)
    {
        $faq = Tentang::find($id);
        if (!$faq || $faq->key !== 'faq') {
            return redirect()->route('main.landing.faq.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $validated = $request->validate([
            'key2' => 'required|string|unique:tentang,key2,' . $id . ',id',
            'value' => 'required|string',
        ], [
            'key2.required' => 'Judul harus diisi',
            'key2.unique' => 'Judul sudah ada',
            'value.required' => 'Jawaban harus diisi',
        ]);

        $faq->update($validated);

        return redirect()->route('main.landing.faq.index')
                       ->with('success', 'FAQ berhasil diperbarui');
    }

    /**
     * Remove the specified FAQ from storage
     */
    public function destroy($id)
    {
        $faq = Tentang::find($id);
        if (!$faq || $faq->key !== 'faq') {
            return redirect()->route('main.landing.faq.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $faq->delete();

        return redirect()->route('main.landing.faq.index')
                       ->with('success', 'FAQ berhasil dihapus');
    }
}
