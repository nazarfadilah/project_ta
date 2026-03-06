<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeritaResource;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $beritas = Berita::with(['admin'])
            ->orderBy('tanggal_publish', 'desc')
            ->paginate($perPage);

        return BeritaResource::collection($beritas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $berita = Berita::create($request->all());
        return new BeritaResource($berita->load(['admin']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $berita)
    {
        return new BeritaResource($berita->load(['admin']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $berita)
    {
        $berita->update($request->all());
        return new BeritaResource($berita->load(['admin']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        $berita->delete();
        return response()->json(['message' => 'Data berita berhasil dihapus']);
    }
}
