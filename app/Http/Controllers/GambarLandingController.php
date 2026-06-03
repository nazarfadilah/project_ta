<?php

namespace App\Http\Controllers;

use App\Models\GambarDashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GambarLandingController extends Controller
{
    /**
     * Display a listing of Gambar Landing data
     */
    public function index()
    {
        $gambarLanding = GambarDashboard::orderBy('posisi', 'asc')->get();
        return view('main.landingPage.gambar_dashboard.index', ['gambarLanding' => $gambarLanding]);
    }

    /**
     * Show the form for creating a new Gambar Landing
     */
    public function create()
    {
        return view('main.landingPage.gambar_dashboard.form', ['gambarLanding' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created Gambar Landing in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi' => 'required|integer|unique:gambar_dashboard,posisi',
            'path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'posisi.required' => 'Posisi harus diisi',
            'posisi.integer' => 'Posisi harus berupa angka',
            'posisi.unique' => 'Posisi sudah ada',
            'path.required' => 'Gambar harus diupload',
            'path.image' => 'File harus berupa gambar',
            'path.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'path.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $filePath = $file->store('gambar-landing', 'public');
            $validated['path'] = $filePath;
        }

        $validated['waktu_upload'] = now();
        GambarDashboard::create($validated);

        return redirect()->route('main.landing.gambar.index')
                       ->with('success', 'Gambar landing berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified Gambar Landing
     */
    public function edit($id)
    {
        $gambarLanding = GambarDashboard::find($id);
        if (!$gambarLanding) {
            return redirect()->route('main.landing.gambar.index')
                           ->with('error', 'Data tidak ditemukan');
        }
        return view('main.landingPage.gambar_dashboard.form', ['gambarLanding' => $gambarLanding, 'mode' => 'edit']);
    }

    /**
     * Update the specified Gambar Landing in storage
     */
    public function update(Request $request, $id)
    {
        $gambarLanding = GambarDashboard::find($id);
        if (!$gambarLanding) {
            return redirect()->route('main.landing.gambar.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        $validated = $request->validate([
            'posisi' => 'required|integer|unique:gambar_dashboard,posisi,' . $id . ',id',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'posisi.required' => 'Posisi harus diisi',
            'posisi.integer' => 'Posisi harus berupa angka',
            'posisi.unique' => 'Posisi sudah ada',
            'path.image' => 'File harus berupa gambar',
            'path.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'path.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($request->hasFile('path')) {
            // Delete old image
            if ($gambarLanding->path && Storage::disk('public')->exists($gambarLanding->path)) {
                Storage::disk('public')->delete($gambarLanding->path);
            }
            $file = $request->file('path');
            $filePath = $file->store('gambar-landing', 'public');
            $validated['path'] = $filePath;
        }

        $gambarLanding->update($validated);

        return redirect()->route('main.landing.gambar.index')
                       ->with('success', 'Gambar landing berhasil diperbarui');
    }

    /**
     * Remove the specified Gambar Landing from storage
     */
    public function destroy($id)
    {
        $gambarLanding = GambarDashboard::find($id);
        if (!$gambarLanding) {
            return redirect()->route('main.landing.gambar.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        // Delete image file
        if ($gambarLanding->path && Storage::disk('public')->exists($gambarLanding->path)) {
            Storage::disk('public')->delete($gambarLanding->path);
        }

        $gambarLanding->delete();

        return redirect()->route('main.landing.gambar.index')
                       ->with('success', 'Gambar landing berhasil dihapus');
    }
}
