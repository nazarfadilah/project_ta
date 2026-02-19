<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GedungResource;
use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $gedungs = Gedung::withCount('ruangans')->paginate($perPage);

        return GedungResource::collection($gedungs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $gedung = Gedung::create($request->all());
        return new GedungResource($gedung);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gedung $gedung)
    {
        return new GedungResource($gedung->load('ruangans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gedung $gedung)
    {
        $gedung->update($request->all());
        return new GedungResource($gedung);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gedung $gedung)
    {
        $gedung->delete();
        return response()->json(['message' => 'Data gedung berhasil dihapus']);
    }
}
