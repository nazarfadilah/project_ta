<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeminjamanTransaksiResource;
use App\Models\PeminjamanTransaksi;
use Illuminate\Http\Request;
use DB;

class PeminjamanTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        
        $peminjamanTransaksis = PeminjamanTransaksi::with([
            'user.profil',
            'ruangan.gedung',
            'admin.profil',
            'detail_peminjaman_saranas.sarana'
        ])->paginate($perPage);

        return PeminjamanTransaksiResource::collection($peminjamanTransaksis);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $peminjamanTransaksi = PeminjamanTransaksi::create($request->all());
        return new PeminjamanTransaksiResource($peminjamanTransaksi->load([
            'user.profil',
            'ruangan.gedung',
            'admin.profil',
            'detail_peminjaman_saranas.sarana'
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(PeminjamanTransaksi $peminjamanTransaksi)
    {
        return new PeminjamanTransaksiResource($peminjamanTransaksi->load([
            'user.profil',
            'ruangan.gedung',
            'admin.profil',
            'detail_peminjaman_saranas.sarana'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeminjamanTransaksi $peminjamanTransaksi)
    {
        $peminjamanTransaksi->update($request->all());
        return new PeminjamanTransaksiResource($peminjamanTransaksi->load([
            'user.profil',
            'ruangan.gedung',
            'admin.profil',
            'detail_peminjaman_saranas.sarana'
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeminjamanTransaksi $peminjamanTransaksi)
    {
        $peminjamanTransaksi->delete();
        return response()->json(['message' => 'Data peminjaman transaksi berhasil dihapus']);
    }
}
