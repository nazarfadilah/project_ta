<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
// use App\Models\Gedung;
use App\Models\MediaFile;
use App\Models\PaketRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index()
    {
        // Code Lama:
        // $ruangans = Ruangan::with('gedung')->orderBy('id_ruangan', 'desc')->get();
        // Code Baru:
        $ruangans = Ruangan::orderBy('id_ruangan', 'desc')->get();
        return view('main.master.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        $ruangan = new Ruangan();
        // Code Lama:
        // $gedungs = Gedung::orderBy('nama_gedung')->get();
        // Code Baru:
        $gedungs = collect();
        $mode = 'create';
        return view('main.master.ruangan.form', compact('ruangan', 'gedungs', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Code Lama:
            // 'gedung_id' => 'required|exists:gedung,id_gedung',
            // Code Baru:
            'gedung_id'       => 'nullable|integer',
            'nama_ruangan'    => 'required|string|max:255',
            'tipe_ruangan'    => 'required|in:KAMAR_STANDAR,KAMAR_VIP,KAMAR_PREMIUM,AULA,RUANG_MEETING,RUANG_LAINNYA',
            'lantai'          => 'nullable|integer',
            'kapasitas'       => 'required|integer|min:1',
            'gender_policy'   => 'nullable|in:MALE_ONLY,FEMALE_ONLY,MIXED',
            'keterangan'      => 'nullable|string',
            'media_files.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pakets'          => 'nullable|array',
            'pakets.*.nama_paket' => 'nullable|string|max:255',
            'pakets.*.harga'      => 'nullable|numeric|min:0',
            'pakets.*.durasi'     => 'nullable|integer|min:1|max:999',
            'pakets.*.status'     => 'nullable|in:ACTIVE,INACTIVE,MAINTENANCE',
        ]);

        // Exclude media_files & pakets from Ruangan creation
        $ruanganData = collect($validated)->except(['media_files', 'pakets'])->toArray();
        $ruangan = Ruangan::create($ruanganData);

        // Handle file uploads
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $mediaPath = \App\Helpers\ImageHelper::optimizeAndStore(
                    $file,
                    'media_file',
                    1200, // Max width
                    null,  // Dynamic height
                    75    // WebP quality
                );

                MediaFile::create([
                    'ruangan_id' => $ruangan->id_ruangan,
                    'path'       => $mediaPath
                ]);
            }
        }

        // Handle Paket Ruangan (hanya jika nama_paket dan harga diisi)
        if ($request->filled('pakets')) {
            foreach ($request->input('pakets') as $paketData) {
                if (!empty($paketData['nama_paket']) && isset($paketData['harga'])) {
                    PaketRuangan::create([
                        'ruangan_id' => $ruangan->id_ruangan,
                        'nama_paket' => $paketData['nama_paket'],
                        'harga'      => $paketData['harga'],
                        'durasi'     => $paketData['durasi'] ?? null,
                        'status'     => $paketData['status'] ?? 'ACTIVE',
                    ]);
                }
            }
        }

        return redirect()->route('main.ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $ruangan = Ruangan::with(['mediaFiles', 'paketRuangans'])->findOrFail($id);
        
        $paketIds = $ruangan->paketRuangans->pluck('id');
        
        $allReviewsQuery = \App\Models\Review::whereIn('transaksi_id', function ($query) use ($paketIds) {
            $query->select('id')
                ->from('peminjaman_transaksi')
                ->whereIn('facilityId', $paketIds);
        });

        $averageRating = $allReviewsQuery->avg('rating') ?: 0;
        $totalReviewsCount = $allReviewsQuery->count();

        $reviews = $allReviewsQuery->with('transaksi.guest.user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('main.master.ruangan.show', compact('ruangan', 'reviews', 'averageRating', 'totalReviewsCount'));
    }

    /**
     * Display all reviews for a room (admin/petugas/pimpinan side)
     */
    public function allReviews($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $paketIds = $ruangan->paketRuangans->pluck('id');
        
        $reviews = \App\Models\Review::with('transaksi.guest.user')
            ->whereIn('transaksi_id', function ($query) use ($paketIds) {
                $query->select('id')
                    ->from('peminjaman_transaksi')
                    ->whereIn('facilityId', $paketIds);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = $ruangan->average_rating;
        
        return view('main.master.ruangan.ulasan', compact('ruangan', 'reviews', 'averageRating'));
    }

    public function edit($id)
    {
        $ruangan = Ruangan::with('mediaFiles')->findOrFail($id);
        // Code Lama:
        // $gedungs = Gedung::orderBy('nama_gedung')->get();
        // Code Baru:
        $gedungs = collect();
        $mode = 'edit';
        return view('main.master.ruangan.form', compact('ruangan', 'gedungs', 'mode'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            // Code Lama:
            // 'gedung_id' => 'required|exists:gedung,id_gedung',
            // Code Baru:
            'gedung_id' => 'nullable|integer',
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
                $mediaPath = \App\Helpers\ImageHelper::optimizeAndStore(
                    $file,
                    'media_file',
                    1200, // Max width
                    null,  // Dynamic height
                    75    // WebP quality
                );

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