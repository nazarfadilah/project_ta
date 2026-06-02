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
        $ruangans = Ruangan::with('gedung')->get();
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
                          ->with('gedung', 'mediaFiles')
                          ->firstOrFail();
        
        return view('users.main.ruangan.form', compact('ruangan'));
    }

    /**
     * Get details of a specific room for AJAX, including packages, media files, and booked dates.
     */
    public function getDetails($id)
    {
        $ruangan = Ruangan::with(['gedung', 'mediaFiles', 'paketRuangans' => function($q) {
            $q->where('status', 'ACTIVE');
        }])->findOrFail($id);

        // Fetch approved or active reservation date ranges to show occupied dates
        $bookedRanges = PeminjamanTransaksi::whereHas('paketRuangan', function ($query) use ($id) {
                $query->where('ruangan_id', $id);
            })
            ->whereIn('statusApproval', ['APPROVED'])
            ->whereIn('statusPeminjaman', ['RESERVASI', 'CHECK_IN', 'SELESAI'])
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
            'gedung' => $ruangan->gedung->nama_gedung ?? '-',
            'kapasitas' => $ruangan->kapasitas,
            'packages' => $ruangan->paketRuangans,
            'photos' => $ruangan->mediaFiles,
            'booked' => $bookedRanges
        ]);
    }
}
