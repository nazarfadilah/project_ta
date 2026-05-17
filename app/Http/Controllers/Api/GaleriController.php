<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GaleriResource;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galeri = Galeri::all();
        return GaleriResource::collection($galeri);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:pengapian,moshulla,aula,gedung',
            'judul' => 'required|string|max:128',
            'isi' => 'required|string',
            'gambar' => 'required|string|max:255',
        ]);

        $galeri = Galeri::create($validated);
        return new GaleriResource($galeri);
    }

    /**
     * Display the specified resource.
     */
    public function show(Galeri $galeri)
    {
        return new GaleriResource($galeri);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'kategori' => 'sometimes|in:pengapian,moshulla,aula,gedung',
            'judul' => 'sometimes|string|max:128',
            'isi' => 'sometimes|string',
            'gambar' => 'sometimes|string|max:255',
        ]);

        $galeri->update($validated);
        return new GaleriResource($galeri);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galeri $galeri)
    {
        $galeri->delete();
        return response()->noContent();
    }
}
