<?php

namespace App\Http\Controllers;

use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        if ($request->input('key') === 'logo') {
            $validated = $request->validate([
                'key' => 'required|string|unique:tentang,key',
                'value_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'key.required' => 'Field key harus diisi',
                'key.unique' => 'Key sudah ada',
                'value_file.required' => 'Logo harus diunggah',
                'value_file.image' => 'File harus berupa gambar',
                'value_file.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
                'value_file.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            if ($request->hasFile('value_file')) {
                $file = $request->file('value_file');
                $path = $file->store('logo', 'public');
                $validated['value'] = 'storage/' . $path;
            }
        } else {
            $validated = $request->validate([
                'key' => 'required|string|unique:tentang,key',
                'value' => 'required|string',
            ], [
                'key.required' => 'Field key harus diisi',
                'key.unique' => 'Key sudah ada',
                'value.required' => 'Field value harus diisi',
            ]);
        }

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

        if ($tentang->key === 'logo') {
            $validated = $request->validate([
                'key' => 'required|string|unique:tentang,key,' . $id . ',id',
                'value_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'key.required' => 'Field key harus diisi',
                'key.unique' => 'Key sudah ada',
                'value_file.image' => 'File harus berupa gambar',
                'value_file.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
                'value_file.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            if ($request->hasFile('value_file')) {
                // Delete old logo file if it is a local file
                if ($tentang->value && str_starts_with($tentang->value, 'storage/')) {
                    $oldPath = str_replace('storage/', '', $tentang->value);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $file = $request->file('value_file');
                $path = $file->store('logo', 'public');
                $validated['value'] = 'storage/' . $path;
            } else {
                $validated['value'] = $tentang->value;
            }
        } else {
            $validated = $request->validate([
                'key' => 'required|string|unique:tentang,key,' . $id . ',id',
                'value' => 'required|string',
            ], [
                'key.required' => 'Field key harus diisi',
                'key.unique' => 'Key sudah ada',
                'value.required' => 'Field value harus diisi',
            ]);
        }

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

        // Delete logo file if it exists and is stored locally
        if ($tentang->key === 'logo' && $tentang->value && str_starts_with($tentang->value, 'storage/')) {
            $oldPath = str_replace('storage/', '', $tentang->value);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $tentang->delete();

        return redirect()->route('main.landing.tentang.index')
                       ->with('success', 'Data tentang berhasil dihapus');
    }
}
