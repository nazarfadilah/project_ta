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
        // Code Lama:
        // $ruangan = Ruangan::where('nama_ruangan', 'like', '%' . $namaRuangan . '%')->with('gedung', 'mediaFiles')->firstOrFail();
        // Code Baru:
        $ruangan = Ruangan::where('nama_ruangan', 'like', '%' . $namaRuangan . '%')
                          ->with('mediaFiles')
                          ->firstOrFail();
        
        return view('users.main.ruangan.form', compact('ruangan'));
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
            ->whereIn('statusApproval', ['APPROVED'])
            ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN', 'SELESAI'])
            ->where('tanggal', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function($item) {
                $start = Carbon::parse($item->jamMulai);
                $end = $start->copy()->addHours($item->durasi);
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
                ->whereIn('statusApproval', ['APPROVED'])
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
