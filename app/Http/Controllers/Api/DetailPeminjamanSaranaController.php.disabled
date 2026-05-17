<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailPeminjamanSaranaResource;
use App\Models\DetailPeminjamanSarana;
use Illuminate\Http\Request;

class DetailPeminjamanSaranaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $detailPeminjamanSaranas = DetailPeminjamanSarana::with('sarana', 'peminjaman_transaksi')->paginate($perPage);

        return DetailPeminjamanSaranaResource::collection($detailPeminjamanSaranas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $detailPeminjamanSarana = DetailPeminjamanSarana::create($request->all());
        return new DetailPeminjamanSaranaResource($detailPeminjamanSarana->load('sarana', 'peminjaman_transaksi'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DetailPeminjamanSarana $detailPeminjamanSarana)
    {
        return new DetailPeminjamanSaranaResource($detailPeminjamanSarana->load('sarana', 'peminjaman_transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetailPeminjamanSarana $detailPeminjamanSarana)
    {
        $detailPeminjamanSarana->update($request->all());
        return new DetailPeminjamanSaranaResource($detailPeminjamanSarana->load('sarana', 'peminjaman_transaksi'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailPeminjamanSarana $detailPeminjamanSarana)
    {
        $detailPeminjamanSarana->delete();
        return response()->json(['message' => 'Data detail peminjaman sarana berhasil dihapus']);
    }
}
