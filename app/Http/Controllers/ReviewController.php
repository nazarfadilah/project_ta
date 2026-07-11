<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanTransaksi;
use App\Models\Review;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show form to write a review.
     */
    public function create($transaksi_id)
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            return redirect()->back()->with('error', 'Akses dibatasi hanya untuk Tamu.');
        }

        // Fetch transaction and make sure it belongs to the logged-in Guest
        $transaksi = PeminjamanTransaksi::where('guestId', $guestId)
            ->where('statusPeminjaman', 'SELESAI')
            ->findOrFail($transaksi_id);

        // Check if already reviewed
        $existingReview = Review::where('transaksi_id', $transaksi->id)->first();
        if ($existingReview) {
            return redirect()->route('users.main.reservasi.index')
                ->with('error', 'Anda sudah memberikan ulasan untuk peminjaman ini.');
        }

        $ruangan = $transaksi->paketRuangan->ruangan;

        return view('users.main.review.form', compact('transaksi', 'ruangan'));
    }

    /**
     * Store review.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            return redirect()->back()->with('error', 'Akses dibatasi hanya untuk Tamu.');
        }

        $validated = $request->validate([
            'transaksi_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
            'foto_ulasan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $transaksi = PeminjamanTransaksi::where('guestId', $guestId)
            ->where('statusPeminjaman', 'SELESAI')
            ->findOrFail($validated['transaksi_id']);

        // Check if already reviewed
        $existingReview = Review::where('transaksi_id', $transaksi->id)->first();
        if ($existingReview) {
            return redirect()->route('users.main.reservasi.index')
                ->with('error', 'Anda sudah memberikan ulasan untuk peminjaman ini.');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_ulasan')) {
            $fotoPath = ImageHelper::optimizeAndStore(
                $request->file('foto_ulasan'),
                'reviews',
                800, // Max width 800px for review photos
                null,
                75   // Quality 75%
            );
        }

        Review::create([
            'transaksi_id' => $transaksi->id,
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'] ?? null,
            'foto_ulasan' => $fotoPath,
        ]);

        return redirect()->route('users.main.reservasi.index')
            ->with('success', 'Terima kasih atas ulasan dan rating Anda!');
    }
}
