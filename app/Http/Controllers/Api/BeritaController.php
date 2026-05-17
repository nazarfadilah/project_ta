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
        
        $beritas = Berita::orderBy('tanggal_publish', 'desc')->paginate($perPage);

        return BeritaResource::collection($beritas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:128',
            'slug' => 'required|string|max:64',
            'isi' => 'required|string',
            'gambar' => 'required|string|max:255',
            'tanggal_publish' => 'required|date',
            'status' => 'required|in:approved,draft,rejected',
            'keterangan' => 'nullable|string',
        ]);

        $berita = Berita::create($validated);
        return new BeritaResource($berita);
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $berita)
    {
        return new BeritaResource($berita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'sometimes|string|max:128',
            'slug' => 'sometimes|string|max:64',
            'isi' => 'sometimes|string',
            'gambar' => 'sometimes|string|max:255',
            'tanggal_publish' => 'sometimes|date',
            'status' => 'sometimes|in:approved,draft,rejected',
            'keterangan' => 'nullable|string',
        ]);

        $berita->update($validated);
        return new BeritaResource($berita);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        $berita->delete();
        return response()->noContent();
    }
}
