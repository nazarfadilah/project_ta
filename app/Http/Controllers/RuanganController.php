<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Gedung;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::with('gedung')->orderBy('id_ruangan', 'desc')->get();
        return view('main.master.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        $ruangan = new Ruangan();
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        $mode = 'create';
        return view('main.master.ruangan.form', compact('ruangan', 'gedungs', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gedung_id' => 'required|exists:gedung,id_gedung',
            'nama_ruangan' => 'required|string|max:255',
            'tipe_ruangan' => 'required|in:KAMAR_STANDAR,KAMAR_VIP,KAMAR_PREMIUM,AULA,RUANG_MEETING,RUANG_LAINNYA',
            'lantai' => 'nullable|integer',
            'kapasitas' => 'required|integer|min:1',
            'gender_policy' => 'nullable|in:MALE_ONLY,FEMALE_ONLY,MIXED',
            'keterangan' => 'nullable|string',
            'media_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Exclude media_files from Ruangan creation
        $ruanganData = collect($validated)->except('media_files')->toArray();
        $ruangan = Ruangan::create($ruanganData);

        // Handle file uploads
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('media_file', 'public');
                $mediaPath = 'storage/' . $path;

                MediaFile::create([
                    'ruangan_id' => $ruangan->id_ruangan,
                    'path' => $mediaPath
                ]);
            }
        }

        return redirect()->route('main.ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return redirect()->route('main.ruangan.edit', $id);
    }

    public function edit($id)
    {
        $ruangan = Ruangan::with('mediaFiles')->findOrFail($id);
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        $mode = 'edit';
        return view('main.master.ruangan.form', compact('ruangan', 'gedungs', 'mode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'gedung_id' => 'required|exists:gedung,id_gedung',
            'nama_ruangan' => 'required|string|max:255',
            'tipe_ruangan' => 'required|in:KAMAR_STANDAR,KAMAR_VIP,KAMAR_PREMIUM,AULA,RUANG_MEETING,RUANG_LAINNYA',
            'lantai' => 'nullable|integer',
            'kapasitas' => 'required|integer|min:1',
            'gender_policy' => 'nullable|in:MALE_ONLY,FEMALE_ONLY,MIXED',
            'keterangan' => 'nullable|string',
            'media_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruanganData = collect($validated)->except('media_files')->toArray();
        $ruangan->update($ruanganData);

        // Handle file uploads
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('media_file', 'public');
                $mediaPath = 'storage/' . $path;

                MediaFile::create([
                    'ruangan_id' => $ruangan->id_ruangan,
                    'path' => $mediaPath
                ]);
            }
        }
        
        return redirect()->route('main.ruangan.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::with('mediaFiles')->findOrFail($id);

        // Delete photos from public disk
        foreach ($ruangan->mediaFiles as $media) {
            $relativePath = str_replace('storage/', '', $media->path);
            Storage::disk('public')->delete($relativePath);
            $media->delete();
        }

        $ruangan->delete();
        
        return redirect()->route('main.ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}