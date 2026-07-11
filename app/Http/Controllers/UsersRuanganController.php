<?php
namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\PeminjamanTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersRuanganController extends Controller
{
    /**
     * Display a listing of ruangans for users (view-only)
     */
    public function index()
    {
        // Code Lama:
        // $ruangans = Ruangan::with('gedung')->get();
        // Code Baru:
        $ruangans = Ruangan::all();
        return view('users.main.ruangan.index', compact('ruangans'));
    }

    /**
     * Display the specified ruangan by slug
     */
    public function show($slug)
    {
        // Convert slug to original name for searching
        $namaRuangan = str_replace('-', ' ', $slug);
        
        // Search for ruangan by name
        $ruangan = Ruangan::where('nama_ruangan', 'like', '%' . $namaRuangan . '%')
                          ->with(['mediaFiles', 'paketRuangans'])
                          ->firstOrFail();

        // Get reviews for this room
        $paketIds = $ruangan->paketRuangans->pluck('id');
        
        $allReviewsQuery = \App\Models\Review::whereIn('transaksi_id', function ($query) use ($paketIds) {
            $query->select('id')
                ->from('peminjaman_transaksi')
                ->whereIn('facilityId', $paketIds);
        });

        // Calculate average rating
        $averageRating = $allReviewsQuery->avg('rating') ?: 0;
        $totalReviewsCount = $allReviewsQuery->count();

        // Take only top 3 reviews
        $reviews = $allReviewsQuery->with('transaksi.guest.user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('users.main.ruangan.form', compact('ruangan', 'reviews', 'averageRating', 'totalReviewsCount'));
    }

    /**
     * Display all reviews for a room (users/tamu side)
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
        
        return view('users.main.ruangan.ulasan', compact('ruangan', 'reviews', 'averageRating'));
    }

    /**
     * Get details of a specific room for AJAX, including packages, media files, and booked dates.
     */
    public function getDetails($id)
    {
        // Code Lama:
        // $ruangan = Ruangan::with(['gedung', 'mediaFiles', 'paketRuangans' => function($q) { $q->where('status', 'ACTIVE'); }])->findOrFail($id);
        // Code Baru:
        $ruangan = Ruangan::with(['mediaFiles', 'paketRuangans' => function($q) {
            $q->where('status', 'ACTIVE');
        }])->findOrFail($id);

        // Fetch approved or active reservation date ranges to show occupied dates
        $bookedRanges = PeminjamanTransaksi::whereHas('paketRuangan', function ($query) use ($id) {
                $query->where('ruangan_id', $id);
            })
            ->whereIn('statusApproval', ['PENDING', 'APPROVED'])
            ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN', 'SELESAI'])
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function($item) {
                $start = Carbon::parse($item->jamMulai);
                $isHarian = ($item->paketRuangan && $item->paketRuangan->tipe_paket == 1);
                if ($isHarian) {
                    $end = $start->copy()->addDays($item->durasi);
                } else {
                    $end = $start->copy()->addHours($item->durasi);
                }
                return [
                    'start' => $start->format('Y-m-d H:i'),
                    'end' => $end->format('Y-m-d H:i'),
                    'label' => $start->format('d M Y H:i') . ' s/d ' . $end->format('d M Y H:i')
                ];
            });

        return response()->json([
            'id_ruangan' => $ruangan->id_ruangan,
            'nama_ruangan' => $ruangan->nama_ruangan,
            'tipe_ruangan' => str_replace('_', ' ', $ruangan->tipe_ruangan),
            // Code Lama:
            // 'gedung' => $ruangan->gedung->nama_gedung ?? '-',
            // Code Baru:
            'gedung' => '-',
            'kapasitas' => $ruangan->kapasitas,
            'keterangan' => $ruangan->keterangan ?? '-',
            'packages' => $ruangan->paketRuangans,
            'photos' => $ruangan->mediaFiles,
            'booked' => $bookedRanges
        ]);
    }

    /**
     * Check availability of rooms on a selected date and room category
     */
    public function ketersediaan(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $kategori = $request->input('kategori', 'ALL');

        // Prepare room query
        // Code Lama:
        // $query = Ruangan::with(['gedung', 'mediaFiles']);
        // Code Baru:
        $query = Ruangan::with(['mediaFiles']);

        // Filter by category
        if ($kategori !== 'ALL') {
            if ($kategori === 'KAMAR') {
                $query->whereIn('tipe_ruangan', ['KAMAR_STANDAR', 'KAMAR_VIP', 'KAMAR_PREMIUM']);
            } else {
                $query->where('tipe_ruangan', $kategori);
            }
        }

        // Filter out rooms that have active/approved booking on the selected date
        if ($tanggal) {
            $bookedRoomIds = PeminjamanTransaksi::whereDate('tanggal', $tanggal)
                ->whereIn('statusApproval', ['PENDING', 'APPROVED'])
                ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN', 'SELESAI'])
                ->whereHas('paketRuangan')
                ->get()
                ->map(function ($t) {
                    return $t->paketRuangan->ruangan_id ?? null;
                })
                ->filter()
                ->unique()
                ->toArray();

            $query->whereNotIn('id_ruangan', $bookedRoomIds);
        }

        $ruangans = $query->get();

        return view('users.main.ruangan.ketersediaan', compact('ruangans', 'tanggal', 'kategori'));
    }
}
