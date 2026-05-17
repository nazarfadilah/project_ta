<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RuanganResource;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        
        $ruangans = Ruangan::with('gedung.admins')->paginate($perPage);

        return RuanganResource::collection($ruangans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ruangan = Ruangan::create($request->all());
        return new RuanganResource($ruangan->load('gedung.admins'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruangan $ruangan)
    {
        return new RuanganResource($ruangan->load('gedung.admins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ruangan $ruangan)
    {
        $ruangan->update($request->all());
        return new RuanganResource($ruangan->load('gedung.admins'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return response()->json(['message' => 'Data ruangan berhasil dihapus']);
    }
}
