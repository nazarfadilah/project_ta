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

    /**
     * Display a list of all reviews written by the logged-in Guest user.
     */
    public function myReviews()
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            return redirect()->back()->with('error', 'Akses dibatasi hanya untuk Tamu.');
        }

        $reviews = Review::whereHas('transaksi', function ($query) use ($guestId) {
            $query->where('guestId', $guestId);
        })->with('transaksi.paketRuangan.ruangan')->orderBy('created_at', 'desc')->get();

        return view('users.main.review.index', compact('reviews'));
    }

    /**
     * Show form to edit an existing review.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            return redirect()->back()->with('error', 'Akses dibatasi hanya untuk Tamu.');
        }

        // Fetch review and ensure it belongs to the logged-in Guest
        $review = Review::whereHas('transaksi', function ($query) use ($guestId) {
            $query->where('guestId', $guestId);
        })->with('transaksi.paketRuangan.ruangan')->findOrFail($id);

        $transaksi = $review->transaksi;
        $ruangan = $transaksi->paketRuangan->ruangan;

        return view('users.main.review.form', compact('review', 'transaksi', 'ruangan'));
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $guestId = $user->guestId;

        if (!$guestId) {
            return redirect()->back()->with('error', 'Akses dibatasi hanya untuk Tamu.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
            'foto_ulasan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $review = Review::whereHas('transaksi', function ($query) use ($guestId) {
            $query->where('guestId', $guestId);
        })->findOrFail($id);

        $fotoPath = $review->foto_ulasan;
        if ($request->hasFile('foto_ulasan')) {
            // Delete old file if exists
            if ($fotoPath && file_exists(public_path($fotoPath))) {
                @unlink(public_path($fotoPath));
            }
            $fotoPath = ImageHelper::optimizeAndStore(
                $request->file('foto_ulasan'),
                'reviews',
                800,
                null,
                75
            );
        }

        $review->update([
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'] ?? null,
            'foto_ulasan' => $fotoPath,
        ]);

        return redirect()->route('users.review.my')
            ->with('success', 'Ulasan Anda berhasil diperbarui!');
    }
}
