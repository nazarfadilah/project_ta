<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaranaResource;
use App\Models\Sarana;
use Illuminate\Http\Request;
use DB;

class SaranaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $saranas = Sarana::paginate($perPage);

        return SaranaResource::collection($saranas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sarana = Sarana::create($request->all());
        return new SaranaResource($sarana);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sarana $sarana)
    {
        return new SaranaResource($sarana);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sarana $sarana)
    {
        $sarana->update($request->all());
        return new SaranaResource($sarana);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sarana $sarana)
    {
        $sarana->delete();
        return response()->json(['message' => 'Data sarana berhasil dihapus']);
    }

    /**
     * GET /api/sarana/ketersediaan-stok
     * Cek stok sarana berdasarkan tanggal peminjaman
     * Query: ?tanggal=2026-02-05
     * 
     * Logika:
     * - Stok tersedia = stok total - jumlah yang dipinjam pada tanggal tersebut
     * - Contoh: jika stok 7, ada peminjaman tgl 5 jumlah 5 buah
     *   Jika cek tgl 5 → stok tersedia 2
     *   Jika cek tgl 4 → stok tersedia 7 (tidak ada peminjaman tgl 4)
     */
    public function ketersediaanStok(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        $perPage = $request->get('per_page', 20);

        // Query semua sarana dengan stok tersedia berdasarkan tanggal
        // Hanya melihat tgl_peminjaman, jika ada peminjaman pada tanggal tersebut, stok berkurang
        $ketersediaanStok = DB::table('sarana as s')
            ->select(
                's.id',
                's.nama',
                's.kondisi',
                's.tgl_penerimaan',
                's.stok as stok_total',
                DB::raw('COALESCE(SUM(dps.jumlah), 0) as stok_dipinjam'),
                DB::raw('s.stok - COALESCE(SUM(dps.jumlah), 0) as stok_tersedia')
            )
            ->leftJoin('detail_peminjaman_sarana as dps', 's.id', '=', 'dps.sarana_id')
            ->leftJoin('peminjaman_transaksi as pt', 'dps.peminjaman_id', '=', 'pt.id')
            ->where(function ($q) use ($tanggal) {
                // Kondisi: hanya hitung peminjaman yang tgl_peminjaman = tanggal yang dicek
                $q->whereNull('pt.id') // Tidak ada peminjaman untuk sarana ini
                  ->orWhereDate('pt.tgl_peminjaman', $tanggal); // Atau ada peminjaman pada tanggal tersebut
            })
            ->groupBy('s.id', 's.nama', 's.kondisi', 's.tgl_penerimaan', 's.stok')
            ->orderBy('s.nama')
            ->paginate($perPage);

        return response()->json([
            'tanggal' => $tanggal,
            'total_items' => $ketersediaanStok->total(),
            'data' => $ketersediaanStok->items(),
            'pagination' => [
                'current_page' => $ketersediaanStok->currentPage(),
                'per_page' => $ketersediaanStok->perPage(),
                'last_page' => $ketersediaanStok->lastPage(),
                'total' => $ketersediaanStok->total(),
            ],
        ]);
    }
}
